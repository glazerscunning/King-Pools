-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2016 at 03:15 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kingpools`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_king_phases`
--

DROP TABLE IF EXISTS `wp_king_phases`;
CREATE TABLE IF NOT EXISTS `wp_king_phases` (
  `phase_id` int(11) NOT NULL,
  `phase_name` tinytext NOT NULL,
  `phase_type` tinytext NOT NULL,
  `phase_trigger_customer_email` tinyint(1) NOT NULL DEFAULT '0',
  `phase_trigger_vendor_email` tinyint(1) NOT NULL DEFAULT '0',
  `phase_vendor_type` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wp_king_phases`
--

INSERT INTO `wp_king_phases` (`phase_id`, `phase_name`, `phase_type`, `phase_trigger_customer_email`, `phase_trigger_vendor_email`, `phase_vendor_type`) VALUES
(1, 'Signed Contract on File', 'signed_contract_on_file', 0, 0, ''),
(2, 'Call In Dig Tess', 'call_in_dig_tess', 0, 0, ''),
(3, 'Get Electric Permit (if needed)', 'get_electric_permit_if_needed', 0, 0, ''),
(4, 'Got Electric Permit', 'got_electric_permit', 0, 0, ''),
(5, 'Get City Or County Permit', 'get_city_or_county_permit', 0, 0, ''),
(6, 'Got City or County Permit', 'got_city_or_county_permit', 1, 0, ''),
(7, 'Do Pool Layout Inspection (If Required)', 'do_pool_layout_inspection_if_required', 0, 0, ''),
(8, 'Schedule Pool Excavation Date', 'schedule_pool_excavation_date', 1, 1, 'excavation'),
(9, 'Order Equipment', 'order_equipment', 0, 0, ''),
(10, 'Excavation Completion', 'excavation_completion', 0, 0, ''),
(11, 'Mark Equipment Location', 'mark_equipment_location', 0, 0, ''),
(12, '1st Draw Payment', '1st_draw_payment', 1, 0, ''),
(13, 'Get Tile, Coping, & Other Material Selections', 'get_tile_coping_other_material_selections', 1, 0, ''),
(14, 'Schedule Pool Plumbing Installation Date', 'schedule_pool_plumbing_installation_date', 1, 1, 'plumbing'),
(15, 'Pool Plumbing Completion', 'pool_plumbing_completion', 0, 0, ''),
(16, 'Schedule Steel Size 3/8" or 1/2"', 'schedule_steel_size_3_8_or_1_2', 0, 0, ''),
(17, 'Schedule Pool Steel Installation Date', 'schedule_pool_steel_installation_date', 1, 1, 'steel'),
(18, 'Pool Steel Completion', 'pool_steel_completion', 0, 0, ''),
(19, 'Schedule Belly Steel Inspections', 'schedule_belly_steel_inspections', 0, 0, ''),
(20, 'Passed Belly Steel Inspections', 'passed_belly_steel_inspections', 1, 0, ''),
(21, 'Schedule Gunite Installation Date', 'schedule_gunite_installation_date', 1, 1, 'concrete'),
(22, 'Gunite Completion', 'gunite_completion', 0, 0, ''),
(23, '2nd Draw Payment', '2nd_draw_payment', 1, 0, ''),
(24, 'Order Sand for Decking and Fill Date', 'order_sand_for_decking_and_fill_date', 0, 0, ''),
(25, 'Sand, Fill Dirt Devilry Completion', 'sand_fill_dirt_devilry_completion', 0, 0, ''),
(26, 'Schedule Landscaper & Customer Meeting Date', 'schedule_landscaper_customer_meeting_date', 1, 0, ''),
(27, 'Schedule Tile, Coping, Masonry Install Date', 'schedule_tile_coping_masonry_install_date', 1, 0, ''),
(28, 'Tile, Coping, Masonry Completion', 'tile_coping_masonry_completion', 0, 0, ''),
(29, 'Schedule Gas Line for Pool Heater Date', 'schedule_gas_line_for_pool_heater_date', 1, 0, ''),
(30, 'Schedule Gas Line for Fire Place or Fire Pit Date', 'schedule_gas_line_for_fire_place_or_fire_pit_date', 1, 0, ''),
(31, 'Pool Gas Line Completion', 'pool_gas_line_completion', 0, 0, ''),
(32, 'Fire Pit & Fire Place Completion', 'fire_pit_fire_place_completion', 0, 0, ''),
(33, 'Schedule Gas Line Inspection Date', 'schedule_gas_line_inspection_date', 0, 0, ''),
(34, 'Passed Gas Line Inspection', 'passed_gas_line_inspection', 1, 0, ''),
(35, 'Schedule P Trap & Back Wash Line Date', 'schedule_p_trap_back_wash_line_date', 1, 0, ''),
(36, 'P Trap & Back Wash Line Completion', 'p_trap_back_wash_line_completion', 0, 0, ''),
(37, 'Schedule P Trap & Back Wash Line Inspection Date', 'schedule_p_trap_back_wash_line_inspection_date', 0, 0, ''),
(38, 'Passed P Trap & Back Wash Line Inspection', 'passed_p_trap_back_wash_line_inspection', 1, 0, ''),
(39, 'Schedule Electrical Rough In Only (if Applicable)', 'schedule_electrical_rough_in_only_if_applicable', 0, 0, ''),
(40, 'Electrical Rough Completion', 'electrical_rough_completion', 0, 0, ''),
(41, 'Schedule Install Full Electrical & "Home Run" Date', 'schedule_install_full_electrical_home_run_date', 1, 1, 'electrical'),
(42, 'Tell Homeowner for Access to Panel', 'tell_homeowner_for_access_to_panel', 1, 0, ''),
(43, 'Pool Electrical Completion', 'pool_electrical_completion', 0, 0, ''),
(44, 'Schedule Pool Electrical Inspections', 'schedule_pool_electrical_inspections', 0, 0, ''),
(45, 'Passed Electrical Inspections', 'passed_electrical_inspections', 1, 0, ''),
(46, 'Schedule Deck/Flat Work Forming Date', 'schedule_deck_flat_work_forming_date', 1, 0, ''),
(47, 'Schedule Sprinkler Repair Install Date', 'schedule_sprinkler_repair_install_date', 1, 0, ''),
(48, 'Confirm Deck/Flat Work material', 'confirm_deck_flat_work_material', 0, 0, ''),
(49, 'Confirm Deck/Flat Work Sq. Ft.', 'confirm_deck_flat_work_sq._ft.', 0, 0, ''),
(50, 'Schedule Deck Steel Inspection', 'schedule_deck_steel_inspection', 0, 0, ''),
(51, 'Passed Deck Steel Inspection', 'passed_deck_steel_inspection', 1, 0, ''),
(52, 'Check Pressure On Plumbing', 'check_pressure_on_plumbing', 0, 0, ''),
(53, 'Check Auto Fill & Overflow for Damage', 'check_auto_fill_overflow_for_damage', 0, 0, ''),
(54, 'Schedule Deck/Flat Work Pouring Date', 'schedule_deck_flat_work_pouring_date', 1, 1, 'concrete'),
(55, 'Deck/Flat Work Completion', 'deck_flat_work_completion', 0, 0, ''),
(56, '3rd Draw Payment', '3rd_draw_payment', 1, 0, ''),
(57, 'Sprinkler Repair Completion', 'sprinkler_repair_completion', 0, 0, ''),
(58, 'Schedule Fire Pit/Fire Place Install Date', 'schedule_fire_pit_fire_place_install_date', 1, 0, ''),
(59, 'Fire Pit/Fire Place Completion', 'fire_pit_fire_place_completion', 0, 0, ''),
(60, 'Schedule Kitchen Install Date', 'schedule_kitchen_install_date', 1, 0, ''),
(61, 'Kitchen Completion', 'kitchen_completion', 0, 0, ''),
(62, 'Schedule Gazebo, Arbor, Patio Cover Date', 'schedule_gazebo_arbor_patio_cover_date', 1, 0, ''),
(63, 'Gazebo, Arbor, Patio Cover Completion', 'gazebo_arbor_patio_cover_completion', 0, 0, ''),
(64, 'Install Mosaics or Tile Spots in Pool', 'install_mosaics_or_tile_spots_in_pool', 0, 0, ''),
(65, 'Schedule Final Plumbing Set Equipment from Long Plumb', 'schedule_final_plumbing_set_equipment_from_long_plumb', 0, 0, ''),
(66, 'Final Equipment Set Completion', 'final_equipment_set_completion', 0, 0, ''),
(67, 'Schedule Final Electrical from Rough In Date', 'schedule_final_electrical_from_rough_in_date', 0, 0, ''),
(68, 'Schedule Final Electrical Inspection from Rough In Date', 'schedule_final_electrical_inspection_from_rough_in_date', 0, 0, ''),
(69, 'Final Electrical from Rough In Completion', 'final_electrical_from_rough_in_completion', 0, 0, ''),
(70, 'Passed Electrical Inspection from Rough In', 'passed_electrical_inspection_from_rough_in', 1, 0, ''),
(71, 'Schedule Clean Up, Extra Dirt, Fence Back Up Date', 'schedule_clean_up_extra_dirt_fence_back_up_date', 1, 1, 'cleanup'),
(72, 'Clean Up, Extra Dirt, Fence Back Up Completion', 'clean_up_extra_dirt_fence_back_up_completion', 0, 0, ''),
(73, 'Schedule Pre Plaster City Inspection In Some Cities', 'schedule_pre_plaster_city_inspection_in_some_cities', 0, 0, ''),
(74, 'Passed Pre Plaster Inspection', 'passed_pre_plaster_inspection', 1, 0, ''),
(75, 'Schedule Plaster/ Pool Interior Finish Install Date', 'schedule_plaster_pool_interior_finish_install_date', 1, 0, ''),
(76, 'Final & All Payments Due Before Plaster Install', 'final_all_payments_due_before_plaster_install', 1, 0, ''),
(77, 'Plaster/ Pool Interior Finish Install Completion', 'plaster_pool_interior_finish_install_completion', 0, 0, ''),
(78, 'Order Start Up Materials', 'order_start_up_materials', 0, 0, ''),
(79, 'Schedule Pool Start Up Date', 'schedule_pool_start_up_date', 1, 0, ''),
(80, 'Schedule Pool School Date', 'schedule_pool_school_date', 1, 0, ''),
(81, 'Start Punch List', 'start_punch_list', 0, 0, ''),
(82, 'Pool Start up Completion', 'pool_start_up_completion', 0, 0, ''),
(83, 'Pool School Completion', 'pool_school_completion', 0, 0, ''),
(84, 'Punch List Completion', 'punch_list_completion', 0, 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_king_phases`
--
ALTER TABLE `wp_king_phases`
  ADD PRIMARY KEY (`phase_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_king_phases`
--
ALTER TABLE `wp_king_phases`
  MODIFY `phase_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=85;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
