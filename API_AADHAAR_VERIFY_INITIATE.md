# API: POST `/v1/aadhaar/verify/initiate`

**Full URL:** `http://localhost:3002/v1/aadhaar/verify/initiate`  
**Method:** `POST`  
**Auth:** None (standalone Aadhaar verification)

---

## What This API Does

1. **Accepts** optional `aadhaarNumber` (12 digits) and optional `transId`.
2. **Validates** Aadhaar format if provided.
3. **Calls** TruthScreen’s e-Aadhaar DigiLocker API to get a **DigiLocker URL**.
4. **Returns** that URL and a transaction ID so the client can open DigiLocker and later poll status via `/v1/aadhaar/verify/status`.

Flow: **Client → Express route → Controller → TruthScreen service (encrypt → HTTP → decrypt) → Response**.

---

## Request

| Field           | Type   | Required | Description                          |
|----------------|--------|----------|--------------------------------------|
| `aadhaarNumber`| string | No       | 12-digit Aadhaar (for reference only)|
| `transId`      | string | No       | Custom transaction ID; default `TS{timestamp}` |

**Example body:**
```json
{
  "aadhaarNumber": "123456789012",
  "transId": "optional-custom-id"
}
```

---

## Response (200 OK)

**Success:**
```json
{
  "success": true,
  "message": "DigiLocker verification initiated successfully",
  "digiLockerUrl": "https://...",
  "tsTransId": "...",
  "transactionId": "TS1234567890",
  "aadhaarNumber": "****-****-9012",
  "data": {
    "tsTransId": "...",
    "digiLockerUrl": "https://...",
    "initiatedAt": "2025-02-24T..."
  }
}
```

**Error (400):** Invalid Aadhaar format or DigiLocker initiation failure (body has `message` and `statusCode`).

---

## Code: Full Flow

### 1. App mount (`src/app.js`)

```javascript
import routes from './routes/v1/index.js';
// ...
app.use('/v1', routes);
```

So `/v1` + `/aadhaar` + `/verify/initiate` = `POST /v1/aadhaar/verify/initiate`.

---

### 2. V1 routes index (`src/routes/v1/index.js`)

```javascript
import aadhaarRoute from './aadhaar.route.js';

const defaultRoutes = [
  // ...
  {
    path: '/aadhaar',
    route: aadhaarRoute,
  },
  // ...
];

defaultRoutes.forEach((route) => {
  router.use(route.path, route.route);
});
```

---

### 3. Aadhaar route (`src/routes/v1/aadhaar.route.js`)

```javascript
import express from 'express';
import * as aadhaarController from '../../controllers/aadhaar.controller.js';

const router = express.Router();

// Standalone Aadhaar verification routes (no authentication required)
router.post('/verify/initiate', aadhaarController.initiateAadhaarVerification);
router.post('/verify/status', aadhaarController.checkAadhaarVerificationStatus);

export default router;
```

---

### 4. Controller (`src/controllers/aadhaar.controller.js`)

```javascript
import httpStatus from 'http-status';
import ApiError from '../utils/ApiError.js';
import { catchAsync } from '../utils/catchAsync.js';
import { initiateAadhaarDigiLockerStandalone, checkAadhaarDigiLockerStatusStandalone } from '../services/truthscreen.service.js';

/**
 * Initiate Aadhaar DigiLocker verification (standalone - no user required)
 */
export const initiateAadhaarVerification = catchAsync(async (req, res) => {
  try {
    const { aadhaarNumber, transId } = req.body;
    
    // Optional Aadhaar number validation
    if (aadhaarNumber) {
      // Basic Aadhaar format validation (12 digits)
      if (!/^[0-9]{12}$/.test(aadhaarNumber)) {
        throw new ApiError(httpStatus.BAD_REQUEST, 'Invalid Aadhaar number format. Should be 12 digits.');
      }
    }

    const result = await initiateAadhaarDigiLockerStandalone(aadhaarNumber, transId);
    console.log("Standalone DigiLocker initiate result ==>", result);

    if (!result.success) {
      throw new ApiError(
        httpStatus.BAD_REQUEST,
        `DigiLocker initiation failed: ${result.message}`
      );
    }

    res.send({
      success: true,
      message: 'DigiLocker verification initiated successfully',
      digiLockerUrl: result.digiLockerUrl,
      tsTransId: result.tsTransId,
      transactionId: result.transactionId,
      aadhaarNumber: aadhaarNumber ? `****-****-${aadhaarNumber.slice(-4)}` : null,
      data: {
        tsTransId: result.tsTransId,
        digiLockerUrl: result.digiLockerUrl,
        initiatedAt: result.initiatedAt
      }
    });
  } catch (error) {
    console.error('Standalone DigiLocker initiation error:', error);
    throw error;
  }
});
```

---

### 5. TruthScreen service – initiate (`src/services/truthscreen.service.js`)

```javascript
import axios from 'axios';
import { encrypt, decrypt } from '../utils/truthscreen.js';

const TRUTHSCREEN_USERNAME = process.env.TRUTHSCREEN_USERNAME;
const TRUTHSCREEN_PASSWORD = process.env.TRUTHSCREEN_PASSWORD;
const TRUTHSCREEN_BASE_URL = process.env.TRUTHSCREEN_BASE_URL;

/**
 * Initiate Aadhaar DigiLocker verification (standalone - no user required)
 * @param {string} aadhaarNumber - Aadhaar number to verify (optional, for reference)
 * @param {string} transId - Transaction ID for the request
 * @returns {Promise<Object>} - DigiLocker link generation result
 */
export const initiateAadhaarDigiLockerStandalone = async (aadhaarNumber = null, transId = null) => {
  try {
    if (!transId) {
      transId = `TS${Date.now()}`;
    }

    const input = {
      trans_id: transId,
      doc_type: "472", // Aadhaar DigiLocker document type
      action: "LINK"
    };

    console.log('📝 Aadhaar DigiLocker Input data:', JSON.stringify(input));
    if (aadhaarNumber) {
      console.log('📝 Aadhaar Number (for reference):', aadhaarNumber);
    }
    
    const encryptedRequest = encrypt(JSON.stringify(input), TRUTHSCREEN_PASSWORD);
    console.log("Aadhaar DigiLocker encryptedRequest", encryptedRequest);
    
    const payload = {
      requestData: encryptedRequest
    };

    const headers = {
      'Content-Type': 'application/json',
      'username': TRUTHSCREEN_USERNAME
    };

    const DIGILOCKER_ENDPOINT = `${TRUTHSCREEN_BASE_URL}/api/v1.0/eaadhaardigilocker/`;
    console.log('🌐 DigiLocker Endpoint:', DIGILOCKER_ENDPOINT);

    const response = await axios.post(DIGILOCKER_ENDPOINT, payload, { headers });
    console.log("📡 DigiLocker Response status:", response.status);
    console.log("📡 DigiLocker Response data:", response.data);
    
    if (response.data && response.data.responseData) {
      const decryptedData = decrypt(response.data.responseData, TRUTHSCREEN_PASSWORD);
      console.log("DigiLocker decryptedData:", decryptedData);
      
      let result;
      if (typeof decryptedData === 'string') {
        try {
          result = JSON.parse(decryptedData);
        } catch (parseError) {
          console.error('JSON parse error:', parseError);
          result = decryptedData;
        }
      } else {
        result = decryptedData;
      }

      return {
        success: Boolean(result.status === 1),
        message: result.msg || 'DigiLocker link generation completed',
        status: result.status === 1 ? 'SUCCESS' : 'FAILED',
        tsTransId: result.ts_trans_id,
        digiLockerUrl: result.data?.url,
        transactionId: transId,
        aadhaarNumber: aadhaarNumber,
        data: result,
        initiatedAt: new Date().toISOString()
      };
    } else {
      console.log('❌ No responseData found for DigiLocker:', response.data);
      return {
        success: false,
        message: 'No response data received from DigiLocker service',
        status: 'ERROR',
        data: response.data,
        transactionId: transId
      };
    }
  } catch (error) {
    console.error('❌ Aadhaar DigiLocker Error Details:', error.response);
    console.error('- Status:', error.response?.status);
    console.error('- Status Text:', error.response?.statusText);
    console.error('- Response Data:', error.response?.data);
    console.error('- Error Message:', error.message);
    
    return {
      success: false,
      message: error.response?.data?.msg || error.message || 'DigiLocker initiation failed',
      status: 'ERROR',
      error: error.response?.data || error.message,
      transactionId: transId || `TS${Date.now()}`
    };
  }
};
```

---

### 6. Encryption util (`src/utils/truthscreen.js`)

```javascript
import crypto from 'crypto';

/**
 * Encrypt data using AES Cipher (CBC) with 128 bit key
 * @param {string} plainText - data to encrypt
 * @param {string} pass - password shared by AuthBridge/TruthScreen
 * @return encrypted data in base64 encoding (payload:base64iv)
 */
function encrypt(plainText, pass) {
  var iv = crypto.randomBytes(16);
  const hash = crypto.createHash('sha512');
  const dataKey = hash.update(pass, 'utf-8');
  const genHash = dataKey.digest('hex');
  const key = genHash.substring(0, 16);
  const cipher = crypto.createCipheriv('aes-128-cbc', Buffer.from(key), iv);
  let requestData = cipher.update(plainText, 'utf-8', 'base64');
  requestData += cipher.final('base64') + ":" + new Buffer(iv).toString('base64');
  return requestData;
}

/**
 * Decrypt data using AES Cipher (CBC) with 128 bit key
 * @param {string} encText - data to be decrypted in base64 encoding (payload:base64iv)
 * @param {string} pass - password shared by AuthBridge/TruthScreen
 * @return decrypted data
 */
function decrypt(encText, pass) {
  var m = crypto.createHash('sha512');
  var datakey = m.update(pass, 'utf-8');
  var genHash = datakey.digest('hex');
  var key = genHash.substring(0, 16);
  var result = encText.split(":");
  var iv = Buffer.from(result[1], 'base64');
  var decipher = crypto.createDecipheriv('aes-128-cbc', Buffer.from(key), iv);
  var decoded = decipher.update(result[0], 'base64', 'utf8');
  decoded += decipher.final('utf8');
  return decoded;
}

export { encrypt, decrypt };
```

---

### 7. Error handling utils

**`src/utils/catchAsync.js`**
```javascript
export const catchAsync = (fn) => (req, res, next) => {
  Promise.resolve(fn(req, res, next)).catch((err) => next(err));
};
```

**`src/utils/ApiError.js`**
```javascript
import httpStatus from 'http-status';

class ApiError extends Error {
  constructor(statusCode, message) {
    super();
    this.statusCode = statusCode;
    this.message = message;
    this.status = `${statusCode}`.startsWith('4') ? 'fail' : 'error';
    this.isOperational = true;
    Error.captureStackTrace(this, this.constructor);
  }
}

export default ApiError;
```

---

## Environment Variables

| Variable               | Description                    |
|------------------------|--------------------------------|
| `TRUTHSCREEN_USERNAME` | TruthScreen API username       |
| `TRUTHSCREEN_PASSWORD` | Shared secret for encrypt/decrypt |
| `TRUTHSCREEN_BASE_URL` | Base URL of TruthScreen API    |

DigiLocker endpoint used: `{TRUTHSCREEN_BASE_URL}/api/v1.0/eaadhaardigilocker/`

---

## Flow Summary

```
POST /v1/aadhaar/verify/initiate
  → app.use('/v1', routes)
  → routes: path '/aadhaar' → aadhaar.route.js
  → router.post('/verify/initiate', initiateAadhaarVerification)
  → aadhaar.controller.initiateAadhaarVerification
      → validate aadhaarNumber (optional, 12 digits)
      → initiateAadhaarDigiLockerStandalone(aadhaarNumber, transId)
  → truthscreen.service
      → transId = transId || 'TS' + Date.now()
      → input = { trans_id, doc_type: "472", action: "LINK" }
      → encrypt(JSON.stringify(input), TRUTHSCREEN_PASSWORD)
      → POST to TRUTHSCREEN_BASE_URL/api/v1.0/eaadhaardigilocker/
          body: { requestData: encryptedRequest }
          headers: Content-Type: application/json, username: TRUTHSCREEN_USERNAME
      → decrypt(response.responseData) → parse JSON
      → return { success, digiLockerUrl, tsTransId, transactionId, initiatedAt, ... }
  → controller: if !result.success throw ApiError(400); else res.send({ success, digiLockerUrl, tsTransId, ... })
```

Client uses `digiLockerUrl` to open DigiLocker and `tsTransId` for `POST /v1/aadhaar/verify/status` to poll until completion.
