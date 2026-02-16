-- SQL script to update all plots with the same image path
-- Run this in your MySQL database (phpMyAdmin, MySQL Workbench, or command line)

UPDATE `plots` 
SET `plot_image` = 'uploads/plots/1771222181_a97e776d724c70dce040.jpg',
    `updated_at` = NOW()
WHERE 1=1;

-- Optional: To see how many plots will be updated, run this first:
-- SELECT COUNT(*) as total_plots FROM `plots`;

-- Optional: To see the current state before update:
-- SELECT id, plot_number, plot_name, plot_image FROM `plots` LIMIT 10;

