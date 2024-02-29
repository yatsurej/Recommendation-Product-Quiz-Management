-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 29, 2024 at 07:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerID` int(11) NOT NULL,
  `answerContent` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answerID`, `answerContent`) VALUES
(59, 'Starting fresh with a morning routine'),
(60, 'Conquering a daily workout'),
(61, 'Unwinding in your happy place'),
(62, 'Indulging in self-care pampering'),
(63, 'Quick and efficient, perfect for on-the-go'),
(64, 'Structured, because champions plan routines'),
(65, 'Indulgent, because skincare is a self-care ritual'),
(66, 'Flexible, because your routine changes with your mood'),
(67, 'Dehydrated — desperate for moisture'),
(68, 'Balanced — not too oily nor dry'),
(69, 'Prone to breakouts and oiliness'),
(70, 'Sensitive and requiring gentle care'),
(71, 'Fight off fine lines'),
(72, 'Reduce appearance of wrinkles'),
(73, 'Even skin tone and reduced dark spots'),
(85, 'Just me — I am my own laundry champion!'),
(86, '2 to 3 — We\'re a solid team doing laundry!'),
(87, '4 or more — I do laundry for everybody!'),
(88, 'Handwashing — I control how I wash my fabrics and linen.'),
(89, 'Washing machine for speed and convenience'),
(90, 'Easy cleaning and stain removal'),
(91, 'Fragrance boosters and fabric softeners'),
(92, 'Liquid detergent'),
(93, 'Powder detergent'),
(94, 'Fresh and floral scents'),
(95, 'Floral anti-bacteria'),
(96, 'Strong, luxurious fragrances'),
(97, 'Daily — for winning moments'),
(98, 'Every other day — finding balance in my hair game'),
(99, '2 - 3 times a week — letting natural oils work'),
(100, 'Less than once a week — I am a low-maintenance champion'),
(101, 'Straight, like a champion\'s determined path'),
(102, 'Wavy, reflecting my dynamic nature'),
(103, 'Curly, exuding resilience and flair'),
(104, 'Frizzy, showing off my rugged strength'),
(105, 'Dryness and brittleness'),
(106, 'Excessive oiliness'),
(107, 'Split ends and breakage'),
(108, 'Damaged strands'),
(109, 'Thin, lifeless hair'),
(110, 'An oily and itchy scalp'),
(111, 'Dry, frizzy strands'),
(113, 'Classic shampoo & conditioner'),
(114, 'Haircare Set — a champion\'s routine in one package'),
(115, 'Haircare made with innovative ingredients'),
(118, 'Serum — the energizing booster'),
(119, 'Moisturizing Cream — the hydration hero'),
(120, 'Skincare Set — the ultimate champions routine in one package'),
(122, 'Retinol to reduce the appearance of wrinkes'),
(123, 'Vitamin C to boost brightness'),
(124, 'Niacinamide to improve overall skin health');

-- --------------------------------------------------------

--
-- Table structure for table `bonus_question`
--

CREATE TABLE `bonus_question` (
  `bqID` int(11) NOT NULL,
  `bqContent` text NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bonus_question`
--

INSERT INTO `bonus_question` (`bqID`, `bqContent`, `categoryID`) VALUES
(1, 'Which skincare ingredient of this product empowers your skin to be an everyday champion?', 4);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` text NOT NULL,
  `categoryDescription` text NOT NULL,
  `userClick` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `categoryName`, `categoryDescription`, `userClick`) VALUES
(4, 'Skin care', 'Take our skincare quiz to find the perfect addition to your beauty routine. Embrace the spirit of the Olympics 2024, and make every day feel like a gold medal win with the right skin-ssential from P&G!', 0),
(5, 'Fabric care', 'Take our fabric care quiz to find the perfect addition to your laundry routine.', 0),
(6, 'Hair care', 'Take our hair care quiz to find the perfect addition to your hair care routine.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `parent_question`
--

CREATE TABLE `parent_question` (
  `pqID` int(11) NOT NULL,
  `pqContent` text NOT NULL,
  `pqNumOptions` tinyint(4) NOT NULL,
  `pqMinAnswer` tinyint(4) NOT NULL,
  `pqOrder` tinyint(4) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_question`
--

INSERT INTO `parent_question` (`pqID`, `pqContent`, `pqNumOptions`, `pqMinAnswer`, `pqOrder`, `categoryID`) VALUES
(43, 'Which everyday ritual makes you feel like a champion?', 4, 1, 1, 4),
(44, 'What is the ideal skincare routing that kickstarts your triumphant day?', 4, 1, 2, 4),
(45, 'How would you describe your skin before and after your daily activities?', 4, 1, 3, 4),
(46, 'What is the ultimate skincare goal for a winner like you?', 3, 1, 4, 4),
(47, 'Which is your perfect skincare companion and your winning MVP (Most Valuable Product)?', 3, 1, 5, 4),
(52, 'How many everyday champions do you do laundry for?', 3, 1, 1, 5),
(53, 'Which laundry method would receive the gold medal?', 2, 1, 2, 5),
(54, 'Which fabric care benefit would keep your clothes clean and ready to conquer the daily challenges with you?', 2, 1, 3, 5),
(55, 'Which P&G fabric care product would complete your winning combination for cleaning, softening, and refreshing your fabrics and linen?', 2, 1, 4, 5),
(56, 'If you had to pick a scent champion for your everyday life, which scent would it be?', 3, 1, 5, 5),
(57, 'How frequently do you treat your hair?', 4, 1, 1, 6),
(58, 'Which hair pattern perfectly describes your hair?', 4, 1, 2, 6),
(59, 'How would you describe your skin before and after your daily activities', 3, 1, 3, 6),
(60, 'Which daily hair challenge are you hoping to overcome?', 4, 1, 4, 6),
(62, 'Which hair care product sounds like the perfect ticket to achieving gold-medal hair?', 3, 1, 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prodID` int(11) NOT NULL,
  `prodName` text NOT NULL,
  `prodDescription` text NOT NULL,
  `prodImage` longblob NOT NULL,
  `prodURL` text NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`prodID`, `prodName`, `prodDescription`, `prodImage`, `prodURL`, `categoryID`) VALUES
(21, 'Olay Retinol 24 Anti-Aging Face Cream Skincare 50g​', 'Olay Regenerist RETINOL24 Facial Moisturizer penetrates deep into skin’s surface layers and visibly reduces wrinkles in just 28 nights. Our proprietary blend of bioavailable Niacinamide + Retinol complex hydrates and renews skin for 24 hours for a bounty of benefits. You’ll see visible improvements in fine lines & wrinkles, smoothness, brightness, firming, dark spots, and pores.\r\n\r\nThis fragrance free and dye free moisturizer absorbs quickly and goes deep into your skin’s surface layers so you wake up every morning to glowing, plumper and younger-looking skin.', 0x75706c6f6164732f4f6c617920526574696e6f6c20323420416e74692d4167696e67204661636520437265616d20536b696e6361726520353067e2808b2e6a7067, 'https://www.lazada.com.ph/products/olay-retinol-24-anti-aging-face-cream-skincare-50g-i3461670970-s17792146918.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BRetinol%252B24%252BAnti-Aging%252BFace%252BCream%252BSkincare%252B50g%253Bnid%253A3461670970%253Bsrc%253ALazadaMainSrp%253Brn%253A769e99d28a8309dc94e3c86cb564b25d%253Bregion%253Aph%253Bsku%253A3461670970_PH%253Bprice%253A1999%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10100737%253Bitem_id%253A3461670970%253Bsku_id%253A17792146918%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=1999&priceCompare=skuId%3A17792146918%3Bsource%3Alazada-search-voucher%3Bsn%3A769e99d28a8309dc94e3c86cb564b25d%3BunionTrace%3A21411e4417074444808962911ea597%3BoriginPrice%3A199900%3BvoucherPrice%3A199900%3BdisplayPrice%3A199900%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444481336&ratingscore=4.972989949748744&request_id=769e99d28a8309dc94e3c86cb564b25d&review=4578&sale=4827&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 4),
(22, 'Olay Niacinamide Anti-Aging Cream Regenerist 50g + Retinol Anti-Aging Cream 50g Skincare​', 'Olay Regenerist Micro-sculpting Day Cream increases cell turnover for a younger-looking skin. It renews skin’s surface layers for smooth texture and refined pores. It provides a lasting hydration that helps to restore skin elasticity.\r\nOlay Regenerist RETINOL24 Facial Moisturizer penetrates deep into skin’s surface layers. Our proprietary blend of Niacinamide + Retinol complex hydrates skin for 24 hours for a bounty of benefits. You’ll see visible improvements in fine lines & wrinkles, smoothness, brightness, firming, dark spots, and pores.', 0x75706c6f6164732f4f6c6179204e696163696e616d69646520416e74692d4167696e6720437265616d20526567656e657269737420353067202b20526574696e6f6c20416e74692d4167696e6720437265616d2035306720536b696e63617265e2808b2e6a7067, 'https://www.lazada.com.ph/products/olay-niacinamide-anti-aging-cream-regenerist-50g-retinol-anti-aging-cream-50g-skincare-i4231855756-s23555707819.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BRetinol%252B24%252BAnti-Aging%252Bset%253Bnid%253A4231855756%253Bsrc%253ALazadaMainSrp%253Brn%253Ae4fe9620309f6b69df6f4d74c07efc88%253Bregion%253Aph%253Bsku%253A4231855756_PH%253Bprice%253A2138.62%253Bclient%253Adesktop%253Bsupplier_id%253A500478848954%253Bbiz_source%253Ah5_internal%253Bslot%253A4%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10100737%253Bitem_id%253A4231855756%253Bsku_id%253A23555707819%253Bshop_id%253A4533275&fastshipping=0&freeshipping=0&fs_ab=2&fuse_fs=&lang=en&location=Metro%20Manila&price=2138.62&priceCompare=skuId%3A23555707819%3Bsource%3Alazada-search-voucher%3Bsn%3Ae4fe9620309f6b69df6f4d74c07efc88%3BunionTrace%3A2140cb2117074445003332638ea970%3BoriginPrice%3A213862%3BvoucherPrice%3A213862%3BdisplayPrice%3A213862%3BsinglePromotionId%3A-1%3BsingleToolCode%3AmockedSalePrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444500732&ratingscore=0&request_id=e4fe9620309f6b69df6f4d74c07efc88&review=&sale=2&search=1&source=search&spm=a2o4l.searchlist.list.4&stock=1', 4),
(23, 'Olay Retinol 24 Anti-Aging Serum Skincare 30ml​', 'Discover our Regenerist Retinol night serum that combines our proprietary blend of Niacinamide + Retinol complex. Olay Regenerist RETINOL24 Night Facial Serum penetrates deep into skin’s surface layers and visibly reduces wrinkles in just 28 nights. You’ll see visible improvements in fine lines & wrinkles, smoothness, brightness, firming, dark spots, and pores. Olay RETINOL24 Night Serum delivers a bounty of skin benefits to that renews skin in 24 hours to wake up to younger looking skin.', 0x75706c6f6164732f4f6c617920526574696e6f6c20323420416e74692d4167696e6720536572756d20536b696e636172652033306d6ce2808b2e6a7067, 'https://www.lazada.com.ph/products/olay-retinol-24-anti-aging-serum-skincare-30ml-i3461807319-s17792144540.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BRetinol%252B24%252BAnti-Aging%252BSerum%252BSkincare%252B30ml%2525E2%252580%25258B%253Bnid%253A3461807319%253Bsrc%253ALazadaMainSrp%253Brn%253A7fef00098a2f7a9accc951965e43bef0%253Bregion%253Aph%253Bsku%253A3461807319_PH%253Bprice%253A2128.8%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_hp%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A13184%253Bitem_id%253A3461807319%253Bsku_id%253A17792144540%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=2128.8&priceCompare=skuId%3A17792144540%3Bsource%3Alazada-search-voucher%3Bsn%3A7fef00098a2f7a9accc951965e43bef0%3BunionTrace%3A2102fc9f17074444640833293e750d%3BoriginPrice%3A212880%3BvoucherPrice%3A212880%3BdisplayPrice%3A212880%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444464491&ratingscore=4.99492385786802&request_id=7fef00098a2f7a9accc951965e43bef0&review=2105&sale=624&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 4),
(24, 'Olay Regenerist Collagen Peptide 24 Serum 30 ml', 'Plump & Bouncy Skin all day long.', 0x75706c6f6164732f4f6c617920526567656e657269737420436f6c6c6167656e205065707469646520323420536572756d203330206d6c2e6a7067, 'https://www.lazada.com.ph/products/olay-regenerist-collagen-peptide-24-serum-30-ml-i3461863106-s17792254209.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BRegenerist%252BCollagen%252BPeptide%252B24%252BSerum%252B30%252Bml%253Bnid%253A3461863106%253Bsrc%253ALazadaMainSrp%253Brn%253A654ad0b9b7280aef3c19e908f95b6761%253Bregion%253Aph%253Bsku%253A3461863106_PH%253Bprice%253A2108.23%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A3%253Butlog_bucket_id%253A470687%253Basc_category_id%253A13184%253Bitem_id%253A3461863106%253Bsku_id%253A17792254209%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=2108.23&priceCompare=skuId%3A17792254209%3Bsource%3Alazada-search-voucher%3Bsn%3A654ad0b9b7280aef3c19e908f95b6761%3BunionTrace%3A21010b7617074445137806856ea54c%3BoriginPrice%3A210823%3BvoucherPrice%3A210823%3BdisplayPrice%3A210823%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444514214&ratingscore=5.0&request_id=654ad0b9b7280aef3c19e908f95b6761&review=286&sale=125&search=1&source=search&spm=a2o4l.searchlist.list.3&stock=1', 4),
(25, 'Olay Regenerist Micro Sculpting Night Cream 50 g', 'Olay Regenerist Micro-sculpting Night Cream increases cell turnover for a younger-looking skin. It renews skin’s surface layers for smooth texture and refined pores. It provides a lasting hydration that helps to restore skin elasticity.', 0x75706c6f6164732f4f6c617920526567656e6572697374204d6963726f205363756c7074696e67204e6967687420437265616d20353020672e6a7067, 'https://www.lazada.com.ph/products/olay-regenerist-micro-sculpting-night-cream-50-g-i3461744732-s17792224379.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BRegenerist%252BMicro%252BSculpting%252BNight%252BCream%252B50%252Bg%253Bnid%253A3461744732%253Bsrc%253ALazadaMainSrp%253Brn%253A410692ef68529801b895a6ff678f9151%253Bregion%253Aph%253Bsku%253A3461744732_PH%253Bprice%253A2130.4%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10100737%253Bitem_id%253A3461744732%253Bsku_id%253A17792224379%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=2130.4&priceCompare=skuId%3A17792224379%3Bsource%3Alazada-search-voucher%3Bsn%3A410692ef68529801b895a6ff678f9151%3BunionTrace%3A21015ed417074445269103645ea509%3BoriginPrice%3A213040%3BvoucherPrice%3A213040%3BdisplayPrice%3A213040%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444527272&ratingscore=4.988095238095238&request_id=410692ef68529801b895a6ff678f9151&review=84&sale=300&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 4),
(29, 'Olay Niacinamide Anti-Aging Day Cream + Night Cream Regenerist Skincare 50g', 'Olay Regenerist Micro-sculpting Day Cream increases cell turnover for a younger-looking skin. It renews skin’s surface layers for smooth texture and refined pores. It provides a lasting hydration that helps to restore skin elasticity.', 0x75706c6f6164732f4f6c6179204e696163696e616d69646520416e74692d4167696e672044617920437265616d202b204e6967687420437265616d20526567656e657269737420536b696e63617265203530672e6a7067, 'Olay Regenerist Micro-sculpting Day Cream increases cell turnover for a younger-looking skin. It renews skin’s surface layers for smooth texture and refined pores. It provides a lasting hydration that helps to restore skin elasticity.', 4),
(31, 'Olay Niacinamide + Vitamin C Brightening Serum Luminous 30ml​', 'REDUCES DARK SPOTS FOR EVEN GLOW\r\n\r\nOlay Luminous Niacinamide + Vitamin C Super Serum is a concentrated formula enriched with\r\n\r\n• BIOAVAILABLE NIACINAMIDE (Vitamin B3) with 99% purity that penetrates 10 layers deep and is easily absorbed by the skin to reveal its even glow from within^\r\n\r\n• STABLE & POTENT VITAMIN C that helps reduce the appearance of dark spots and discoloration ', 0x75706c6f6164732f4f6c6179204e696163696e616d696465202b20566974616d696e204320427269676874656e696e6720536572756d204c756d696e6f75732033306d6ce2808b2e6a7067, 'https://www.lazada.com.ph/products/olay-niacinamide-vitamin-c-brightening-serum-luminous-30ml-i3461691824-s17792128345.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BNiacinamide%252B%25252B%252BVitamin%252BC%252BBrightening%252BSerum%252BLuminous%252B30ml%2525E2%252580%25258B%253Bnid%253A3461691824%253Bsrc%253ALazadaMainSrp%253Brn%253A7d789fdbc93232c3ecc4bc7fb614d1f4%253Bregion%253Aph%253Bsku%253A3461691824_PH%253Bprice%253A2098.95%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A13169%253Bitem_id%253A3461691824%253Bsku_id%253A17792128345%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=2098.95&priceCompare=skuId%3A17792128345%3Bsource%3Alazada-search-voucher%3Bsn%3A7d789fdbc93232c3ecc4bc7fb614d1f4%3BunionTrace%3A2102fcdf17074447389021244e996d%3BoriginPrice%3A209895%3BvoucherPrice%3A209895%3BdisplayPrice%3A209895%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444739326&ratingscore=4.976303317535545&request_id=7d789fdbc93232c3ecc4bc7fb614d1f4&review=1332&sale=1928&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 4),
(32, 'Olay Niacinamide + Vitamin C Brightening Cream Luminous 50g', 'Instantly hydrates skin, and contains Niacinamide to reduce the look of dark spots.', 0x75706c6f6164732f4f6c6179204e696163696e616d696465202b20566974616d696e204320427269676874656e696e6720437265616d204c756d696e6f7573203530672e706e67, 'https://www.lazada.com.ph/products/olay-niacinamide-vitamin-c-brightening-cream-luminous-50g-i4291749656-s24070493768.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BNiacinamide%252B%25252B%252BVitamin%252BC%252BBrightening%252BSerum%252BLuminous%252B30ml%2525E2%252580%25258B%253Bnid%253A4291749656%253Bsrc%253ALazadaMainSrp%253Brn%253A7d789fdbc93232c3ecc4bc7fb614d1f4%253Bregion%253Aph%253Bsku%253A4291749656_PH%253Bprice%253A1999%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A3%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10100737%253Bitem_id%253A4291749656%253Bsku_id%253A24070493768%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=1999&priceCompare=skuId%3A24070493768%3Bsource%3Alazada-search-voucher%3Bsn%3A7d789fdbc93232c3ecc4bc7fb614d1f4%3BunionTrace%3A2102fcdf17074447389021244e996d%3BoriginPrice%3A199900%3BvoucherPrice%3A199900%3BdisplayPrice%3A199900%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444739326&ratingscore=5.0&request_id=7d789fdbc93232c3ecc4bc7fb614d1f4&review=2&sale=31&search=1&source=search&spm=a2o4l.searchlist.list.3&stock=1', 4),
(33, 'Olay Luminous Vitamin C Cream 50g + Luminous Vitamin C Serum 30ml Regimen Bundle', 'REDUCES DARK SPOTS FOR EVEN GLOW\r\n\r\nOlay Luminous Niacinamide + Vitamin C Super Serum is a concentrated formula enriched with\r\n\r\n• BIOAVAILABLE NIACINAMIDE (Vitamin B3) with 99% purity that penetrates 10 layers deep and is easily absorbed by the skin to reveal its even glow from within^\r\n\r\n• STABLE & POTENT VITAMIN C that helps reduce the appearance of dark spots and discoloration ', 0x75706c6f6164732f4f6c6179204c756d696e6f757320566974616d696e204320437265616d20353067202b204c756d696e6f757320566974616d696e204320536572756d2033306d6c20526567696d656e2042756e646c652e6a7067, 'https://www.lazada.com.ph/products/limited-time-only-olay-luminous-vitamin-c-cream-50g-luminous-vitamin-c-serum-30ml-regimen-bundle-pack-i4118369305-s22824546332.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AOlay%252BNiacinamide%252B%25252B%252BVitamin%252BC%252BBrightening%252BSerum%252BLuminous%252B30ml%2525E2%252580%25258B%253Bnid%253A4118369305%253Bsrc%253ALazadaMainSrp%253Brn%253A7d789fdbc93232c3ecc4bc7fb614d1f4%253Bregion%253Aph%253Bsku%253A4118369305_PH%253Bprice%253A2499%253Bclient%253Adesktop%253Bsupplier_id%253A500256546732%253Bbiz_source%253Ah5_internal%253Bslot%253A21%253Butlog_bucket_id%253A470687%253Basc_category_id%253A10100737%253Bitem_id%253A4118369305%253Bsku_id%253A22824546332%253Bshop_id%253A3827212&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=2499&priceCompare=skuId%3A22824546332%3Bsource%3Alazada-search-voucher%3Bsn%3A7d789fdbc93232c3ecc4bc7fb614d1f4%3BunionTrace%3A2102fcdf17074447389021244e996d%3BoriginPrice%3A249900%3BvoucherPrice%3A249900%3BdisplayPrice%3A249900%3BsinglePromotionId%3A900000021797276%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707444739326&ratingscore=5.0&request_id=7d789fdbc93232c3ecc4bc7fb614d1f4&review=55&sale=154&search=1&source=search&spm=a2o4l.searchlist.list.21&stock=1', 4),
(39, 'Pantene Pro-V Total Damage Care Shampoo 1.2L', 'test', 0x75706c6f6164732f50616e74656e652050726f2d5620546f74616c2044616d6167652043617265205368616d706f6f20312e324c2e706e67, 'https://www.lazada.com.ph/products/pantene-pro-v-total-damage-care-shampoo-12l-i3466449278-s17823647227.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253APantene%252BPro%252BV%252BTotal%252Bdamage%253Bnid%253A3466449278%253Bsrc%253ALazadaMainSrp%253Brn%253A0655c7e14186ca37a18b15dfbe9d152a%253Bregion%253Aph%253Bsku%253A3466449278_PH%253Bprice%253A613.33%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A5612%253Bitem_id%253A3466449278%253Bsku_id%253A17823647227%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=613.33&priceCompare=skuId%3A17823647227%3Bsource%3Alazada-search-voucher%3Bsn%3A0655c7e14186ca37a18b15dfbe9d152a%3BunionTrace%3A213bd36917075681740981841e9c92%3BoriginPrice%3A61333%3BvoucherPrice%3A61333%3BdisplayPrice%3A61333%3BsinglePromotionId%3A900000023758210%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707568174548&ratingscore=4.9937888198757765&request_id=0655c7e14186ca37a18b15dfbe9d152a&review=710&sale=1939&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 6),
(40, 'Herbal Essences Shampoo and Conditioner Repair Argan Oil of Morocco Hair Care 600ml + 600ml', 'Herbal essences, aloe vera shampoo and conditioner, argan oil, shampoo, conditioner, shampoo and conditioner, hair treatment, hair shampoo, serum, anti dandruff shampoo , Hair Fall, Hair care, Clean, Hydrate, Repair, Revitalise, Scalp, Smooth, Treatment, Haircare, Blue Ginger, Coconut Milk, Golden Moringa Oil, Tea Tree Oil, White Strawberry', 0x75706c6f6164732f48657262616c20457373656e636573205368616d706f6f20616e6420436f6e646974696f6e65722052657061697220417267616e204f696c206f66204d6f726f63636f20486169722043617265203630306d6c202b203630306d6c2e6a7067, 'https://www.lazada.com.ph/products/herbal-essences-shampoo-and-conditioner-repair-argan-oil-of-morocco-hair-care-600ml-600ml-i3521429538-s18141366045.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Aherbal%252Bessences%252Bmorocco%252Bshampoo%252Bconditioner%252Bset%253Bnid%253A3521429538%253Bsrc%253ALazadaMainSrp%253Brn%253Add8aae2b1dacb15bf0c4f25adb9287b7%253Bregion%253Aph%253Bsku%253A3521429538_PH%253Bprice%253A883.5%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A9433%253Bitem_id%253A3521429538%253Bsku_id%253A18141366045%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=883.5&priceCompare=skuId%3A18141366045%3Bsource%3Alazada-search-voucher%3Bsn%3Add8aae2b1dacb15bf0c4f25adb9287b7%3BunionTrace%3A21010b7a17075696133238692ebfad%3BoriginPrice%3A88350%3BvoucherPrice%3A88350%3BdisplayPrice%3A88350%3BsinglePromotionId%3A900000023758210%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707569613791&ratingscore=4.978930307941654&request_id=dd8aae2b1dacb15bf0c4f25adb9287b7&review=1280&sale=1917&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 6),
(41, 'Pantene Conditioner Collagen Damage Repair Hair Care 480ml', 'The formula contains Pro-Vitamin B5, tested and certified by world-renowned vitamin expert, the Swiss Vitamin Institute.', 0x75706c6f6164732f50616e74656e6520436f6e646974696f6e657220436f6c6c6167656e2044616d6167652052657061697220486169722043617265203438306d6c2e6a7067, 'https://www.lazada.com.ph/products/pantene-collagen-repair-pro-v-3-minute-miracle-conditioner-total-damage-care-300ml-2-pieces-i3504388756-s18049048891.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253APantene%252BPro-V%252BTotal%252BDamage%252BCare%252Bconditioner%253Bnid%253A3504388756%253Bsrc%253ALazadaMainSrp%253Brn%253A7bbfc71b89061fc97524b4e8eb21de61%253Bregion%253Aph%253Bsku%253A3504388756_PH%253Bprice%253A529.31%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24120%253Bitem_id%253A3504388756%253Bsku_id%253A18049048891%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=529.31&priceCompare=skuId%3A18049048891%3Bsource%3Alazada-search-voucher%3Bsn%3A7bbfc71b89061fc97524b4e8eb21de61%3BunionTrace%3A2101406f17075682335205643eac68%3BoriginPrice%3A52931%3BvoucherPrice%3A52931%3BdisplayPrice%3A52931%3BsinglePromotionId%3A900000023758210%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707568233960&ratingscore=4.982978723404256&request_id=7bbfc71b89061fc97524b4e8eb21de61&review=531&sale=659&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 6),
(42, 'Pantene Biotin Strength Pro-V 3-Minute Miracle Conditioner', 'Say goodbye to hair fall due to breakage! Strengthen your hair with Pantene 3 Minute Miracle Biotin Strength Conditioner. It not only repairs 3 months of damage in just 3 minutes, it also gives lasting strength that reduces hair fall due to breakage.', 0x75706c6f6164732f50616e74656e652042696f74696e20537472656e6774682050726f2d5620332d4d696e757465204d697261636c6520436f6e646974696f6e6572205b486169722046616c6c20436f6e74726f6c5d203330306d6c20283220706965636573292e6a7067, 'https://www.lazada.com.ph/products/pantene-biotin-strength-pro-v-3-minute-miracle-conditioner-hair-fall-control-300ml-2-pieces-i3504488016-s18049035332.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253APantene%252BBiotin%252BStrength%252BPro-V%252B3-Minute%252BMiracle%252BConditioner%253Bnid%253A3504488016%253Bsrc%253ALazadaMainSrp%253Brn%253Ad6217a24d0fb5fd33d578f8bd209f935%253Bregion%253Aph%253Bsku%253A3504488016_PH%253Bprice%253A529.31%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24120%253Bitem_id%253A3504488016%253Bsku_id%253A18049035332%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=529.31&priceCompare=skuId%3A18049035332%3Bsource%3Alazada-search-voucher%3Bsn%3Ad6217a24d0fb5fd33d578f8bd209f935%3BunionTrace%3A214103bd17076972449232263e3f0f%3BoriginPrice%3A52931%3BvoucherPrice%3A52931%3BdisplayPrice%3A52931%3BsinglePromotionId%3A900000023758210%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707697245391&ratingscore=4.971590909090909&request_id=d6217a24d0fb5fd33d578f8bd209f935&review=466&sale=540&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 6),
(44, 'Pantene Pro-V Hair Fall Control Shampoo 1.2L', 'A hair fall treatment shampoo like no other. Pantene Hair Fall Control Shampoo strengthens your hair from root to tip. This shampoo is formulated with Pantene\'s nourishing blend of Pro-Vitamins, to protect hair from damage that results in breakage.', 0x75706c6f6164732f50616e74656e652050726f2d5620486169722046616c6c20436f6e74726f6c205368616d706f6f20312e324c2e6a7067, 'https://www.lazada.com.ph/products/pantene-pro-v-hair-fall-control-shampoo-12l-i3466418425-s17823522706.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253APantene%252BPro%252BV%252BHair%252BFall%252Bcontrol%252Bshampoo%253Bnid%253A3466418425%253Bsrc%253ALazadaMainSrp%253Brn%253A0d150fdd5bbd1cedfd276b6068b576d3%253Bregion%253Aph%253Bsku%253A3466418425_PH%253Bprice%253A613.33%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A1%253Butlog_bucket_id%253A470687%253Basc_category_id%253A5612%253Bitem_id%253A3466418425%253Bsku_id%253A17823522706%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=613.33&priceCompare=skuId%3A17823522706%3Bsource%3Alazada-search-voucher%3Bsn%3A0d150fdd5bbd1cedfd276b6068b576d3%3BunionTrace%3A2140cb9d17075684167332489eb081%3BoriginPrice%3A61333%3BvoucherPrice%3A61333%3BdisplayPrice%3A61333%3BsinglePromotionId%3A900000023758210%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707568417205&ratingscore=4.9743589743589745&request_id=0d150fdd5bbd1cedfd276b6068b576d3&review=922&sale=2601&search=1&source=search&spm=a2o4l.searchlist.list.1&stock=1', 6),
(45, 'Head and Shoulders Shampoo Anti Dandruff Cool Menthol Hair Care 1200ml', 'COOL MENTHOL+\r\nOUR BEST EVER FORMULA+\r\nLEAVES SCALP COOL & REFRESHED\r\nWith over 50 years expertise in antidandruff technology, H&S innovative shampoo with Magnetic Lifting Foam', 0x75706c6f6164732f4865616420616e642053686f756c64657273205368616d706f6f20416e74692044616e647275666620436f6f6c204d656e74686f6c2048616972204361726520313230306d6c2e6a7067, 'https://www.lazada.com.ph/products/head-and-shoulders-shampoo-anti-dandruff-cool-menthol-hair-care-1200ml-i3466481201-s17823651148.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AHead%252Band%252BShoulder%252BCool%252Bmenthol%252Bshampoo%253Bnid%253A3466481201%253Bsrc%253ALazadaMainSrp%253Brn%253A0bb52716afd958a20a58519c8209ded9%253Bregion%253Aph%253Bsku%253A3466481201_PH%253Bprice%253A639%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A5612%253Bitem_id%253A3466481201%253Bsku_id%253A17823651148%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=639&priceCompare=skuId%3A17823651148%3Bsource%3Alazada-search-voucher%3Bsn%3A0bb52716afd958a20a58519c8209ded9%3BunionTrace%3A21010b2417075687075342466ec281%3BoriginPrice%3A63900%3BvoucherPrice%3A63900%3BdisplayPrice%3A63900%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707568707945&ratingscore=4.976385700229583&request_id=0bb52716afd958a20a58519c8209ded9&review=4993&sale=8631&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 6),
(46, 'Head and Shoulders Shampoo Anti Dandruff Smooth & Silky Hair Care 1200ml', '3 ACTION FORMULA: Cleanses, Protects, and Moisturizes\r\nEffectively removes dandruff and prevents it from coming back.\r\nSoft, smooth hair visible flakes only, with regular use\r\nMoisturizes hair from root to tips', 0x75706c6f6164732f4865616420616e642053686f756c64657273205368616d706f6f20416e74692044616e647275666620536d6f6f746820262053696c6b792048616972204361726520313230306d6c2e6a7067, 'https://www.lazada.com.ph/products/head-and-shoulders-shampoo-anti-dandruff-smooth-silky-hair-care-1200ml-i3466329857-s17823428870.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253AHead%252Band%252BShoulder%252Bsmooth%252B%252526%252Bsilky%253Bnid%253A3466329857%253Bsrc%253ALazadaMainSrp%253Brn%253A89763666fcc06b188dcb54c357446dda%253Bregion%253Aph%253Bsku%253A3466329857_PH%253Bprice%253A639%253Bclient%253Adesktop%253Bsupplier_id%253A500256555655%253Bbiz_source%253Ah5_internal%253Bslot%253A3%253Butlog_bucket_id%253A470687%253Basc_category_id%253A5612%253Bitem_id%253A3466329857%253Bsku_id%253A17823428870%253Bshop_id%253A3827241&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=639&priceCompare=skuId%3A17823428870%3Bsource%3Alazada-search-voucher%3Bsn%3A89763666fcc06b188dcb54c357446dda%3BunionTrace%3A2102fc1317075689035344590e79b1%3BoriginPrice%3A63900%3BvoucherPrice%3A63900%3BdisplayPrice%3A63900%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707568903954&ratingscore=4.984861976847729&request_id=89763666fcc06b188dcb54c357446dda&review=3254&sale=6329&search=1&source=search&spm=a2o4l.searchlist.list.3&stock=1', 6),
(47, '[Bundle of 2] Ariel Liquid Detergent Floral Passion Sunrise Fresh Bundle 2.105 - 2.16KG Refill (Laundry, Detergent)', '10x Cleaning Enzyme\r\nWith Perfume For Long Lasting Fragrance\r\nDeeply penetrates to remove stains better\r\nFresh smelling scent', 0x75706c6f6164732f5b42756e646c65206f6620325d20417269656c204c697175696420446574657267656e7420466c6f72616c2050617373696f6e2053756e726973652046726573682042756e646c6520322e313035202d20322e31364b4720526566696c6c20284c61756e6472792c20446574657267656e74292e6a7067, 'https://www.lazada.com.ph/products/bundle-of-2-ariel-liquid-detergent-floral-passion-sunrise-fresh-bundle-2105-216kg-refill-laundry-detergent-i3531968772-s18204459791.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Aariel%252Brefill%253Bnid%253A3531968772%253Bsrc%253ALazadaMainSrp%253Brn%253Af4001a2db77bce2ae39b52c016480c92%253Bregion%253Aph%253Bsku%253A3531968772_PH%253Bprice%253A865.3%253Bclient%253Adesktop%253Bsupplier_id%253A500256573541%253Bbiz_source%253Ah5_internal%253Bslot%253A11%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24428%253Bitem_id%253A3531968772%253Bsku_id%253A18204459791%253Bshop_id%253A3827162&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=865.3&priceCompare=skuId%3A18204459791%3Bsource%3Alazada-search-voucher%3Bsn%3Af4001a2db77bce2ae39b52c016480c92%3BunionTrace%3A2102fcc817079082893092215e8576%3BoriginPrice%3A86530%3BvoucherPrice%3A86530%3BdisplayPrice%3A86530%3BsinglePromotionId%3A900000023788495%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707908289702&ratingscore=4.9860335195530725&request_id=f4001a2db77bce2ae39b52c016480c92&review=4534&sale=9286&search=1&source=search&spm=a2o4l.searchlist.list.11&stock=1', 5),
(48, 'Tide Powder Detergent Perfume Fantasy Original White & Bright Garden Bloom 3.50 - 3.52KG Pouch (Laundry, Detergent Powder)', 'Outstanding Clean and Stubborn Stain Removal\r\nColor Care Technology Keeps Whites Bright and Clothes Looking New\r\nDoes not leave behind powder residue on clothes and machine\r\nEasy Clean, Easy Rinse - Designed to work with Semi-Automatic Washing Machines and While hand Washing', 0x75706c6f6164732f5469646520506f7764657220446574657267656e742050657266756d652046616e74617379204f726967696e616c2057686974652026204272696768742047617264656e20426c6f6f6d20332e3530202d20332e35324b4720506f75636820284c61756e6472792c20446574657267656e7420506f77646572292e6a7067, 'https://www.lazada.com.ph/products/tide-powder-detergent-perfume-fantasy-original-white-bright-garden-bloom-350-352kg-pouch-laundry-detergent-powder-i3532228174-s18205292351.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Atide%252Bpowder%252Bdetergent%252B3%253Bnid%253A3532228174%253Bsrc%253ALazadaMainSrp%253Brn%253A5bd03360d5186edc31be9dfa011ec31d%253Bregion%253Aph%253Bsku%253A3532228174_PH%253Bprice%253A584.1%253Bclient%253Adesktop%253Bsupplier_id%253A500256573541%253Bbiz_source%253Ah5_internal%253Bslot%253A2%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24434%253Bitem_id%253A3532228174%253Bsku_id%253A18205292351%253Bshop_id%253A3827162&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Laguna&price=584.1&priceCompare=skuId%3A18205292351%3Bsource%3Alazada-search-voucher%3Bsn%3A5bd03360d5186edc31be9dfa011ec31d%3BunionTrace%3A21010b7817079084884896255eae17%3BoriginPrice%3A58410%3BvoucherPrice%3A58410%3BdisplayPrice%3A58410%3BsinglePromotionId%3A900000023788495%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707908488942&ratingscore=4.994469026548672&request_id=5bd03360d5186edc31be9dfa011ec31d&review=6538&sale=5218&search=1&source=search&spm=a2o4l.searchlist.list.2&stock=1', 5),
(49, '[Bundle of 2] Downy Fabric Conditioner Sunrise Fresh Garden Bloom 1.48L Refill Blue Pink (fabcon, fabric softener, fabric)', '● Use Everyday for Everlasting lasting KAPIT-BANGO* Laban sa Bad Odor\r\n● Cottony Fresh with Iwas-Lagkit Technology\r\n● #1 Family\'s Choice for Fabric Conditioner\r\n● Suitable for All Washing Machines', 0x75706c6f6164732f5b42756e646c65206f6620325d20446f776e792046616272696320436f6e646974696f6e65722053756e726973652046726573682047617264656e20426c6f6f6d20312e34384c20526566696c6c20426c75652050696e6b2028666162636f6e2c2066616272696320736f6674656e65722c20666162726963292e6a7067, 'https://www.lazada.com.ph/products/bundle-of-2-downy-fabric-conditioner-sunrise-fresh-garden-bloom-148l-refill-blue-pink-fabcon-fabric-softener-fabric-i3531756283-s18203650564.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Adowny%252Bsunrise%252Bfresh%253Bnid%253A3531756283%253Bsrc%253ALazadaMainSrp%253Brn%253Aed6f0d1930a533ab803e0abc09be5f76%253Bregion%253Aph%253Bsku%253A3531756283_PH%253Bprice%253A576.46%253Bclient%253Adesktop%253Bsupplier_id%253A500256573541%253Bbiz_source%253Ah5_internal%253Bslot%253A2%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24429%253Bitem_id%253A3531756283%253Bsku_id%253A18203650564%253Bshop_id%253A3827162&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=576.46&priceCompare=skuId%3A18203650564%3Bsource%3Alazada-search-voucher%3Bsn%3Aed6f0d1930a533ab803e0abc09be5f76%3BunionTrace%3A2140e7b217079085758755704e9539%3BoriginPrice%3A57646%3BvoucherPrice%3A57646%3BdisplayPrice%3A57646%3BsinglePromotionId%3A900000023788495%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707908576291&ratingscore=4.991746905089409&request_id=ed6f0d1930a533ab803e0abc09be5f76&review=3417&sale=4333&search=1&source=search&spm=a2o4l.searchlist.list.2&stock=1', 5),
(50, '[Bundle of 2] Downy Fabric Conditioner Kontra Germs Kontra Kulob Spring Blossom 690ml Refill Green (fabcon, fabric softener, fabric)', 'With 99.9% Protection mula sa germ growth na sanhi ng baho+\r\nUse everyday for Everlasting KAPIT-BANGO*\r\nDowny Antibac Kontra Germs from the makers of Safeguard Family Germ Protection\r\nConcentrate Fabric Conditioner with Long-lasting Fragrance, Malodor Prevention, Easy Iron and Suitable for baby clothes\r\nSoften, Freshen and Protect Clothes You Love with Downy Fabcon Liquid Conditioner', 0x75706c6f6164732f5b42756e646c65206f6620325d20446f776e792046616272696320436f6e646974696f6e6572204b6f6e747261204765726d73204b6f6e747261204b756c6f6220537072696e6720426c6f73736f6d203639306d6c20526566696c6c20477265656e2028666162636f6e2c2066616272696320736f6674656e65722c20666162726963292e6a7067, 'https://www.lazada.com.ph/products/bundle-of-2-downy-fabric-conditioner-kontra-germs-kontra-kulob-spring-blossom-690ml-refill-green-fabcon-fabric-softener-fabric-i3531863570-s18204075627.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Adowny%252Bkontra%252B690%253Bnid%253A3531863570%253Bsrc%253ALazadaMainSrp%253Brn%253A31ef0d23d6ceaec4b1571f04dcbb4da8%253Bregion%253Aph%253Bsku%253A3531863570_PH%253Bprice%253A281.44%253Bclient%253Adesktop%253Bsupplier_id%253A500256573541%253Bbiz_source%253Ah5_internal%253Bslot%253A1%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24429%253Bitem_id%253A3531863570%253Bsku_id%253A18204075627%253Bshop_id%253A3827162&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=281.44&priceCompare=skuId%3A18204075627%3Bsource%3Alazada-search-voucher%3Bsn%3A31ef0d23d6ceaec4b1571f04dcbb4da8%3BunionTrace%3A21010acc17079087752162913e70c8%3BoriginPrice%3A28144%3BvoucherPrice%3A28144%3BdisplayPrice%3A28144%3BsinglePromotionId%3A900000023788495%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707908775672&ratingscore=4.986959287531807&request_id=31ef0d23d6ceaec4b1571f04dcbb4da8&review=5620&sale=10160&search=1&source=search&spm=a2o4l.searchlist.list.1&stock=1', 5),
(51, '[Bundle of 2] Downy Fabric Conditioner Passion Mystique Blissful Blossom French Lavender Fresh Bouquet Bundle 600ml - 640ml Refill Red Black Pink Purple (fabcon, fabric softener, fabric)', 'With 24 Hour Long Lasting-Perfume\r\nMotion-activated freshness when moving, when sweating, perfume freshness release\r\n3 Weeks Long lasting scent* with Perfume Pearl technology\r\nShelf Life: 1095 days', 0x75706c6f6164732f5b42756e646c65206f6620325d20446f776e792046616272696320436f6e646974696f6e65722050617373696f6e204d7973746971756520426c69737366756c20426c6f73736f6d204672656e6368204c6176656e64657220467265736820426f75717565742042756e646c65203630306d6c202d203634306d6c20526566696c6c2052656420426c61636b2050696e6b20507572706c652028666162636f6e2c2066616272696320736f6674656e65722c20666162726963292e6a7067, 'https://www.lazada.com.ph/products/bundle-of-2-downy-fabric-conditioner-passion-mystique-blissful-blossom-french-lavender-fresh-bouquet-bundle-600ml-640ml-refill-red-black-pink-purple-fabcon-fabric-softener-fabric-i3540020292-s18242546621.html?c=&channelLpJumpArgs=&clickTrackInfo=query%253Adowny%252Bpassion%253Bnid%253A3540020292%253Bsrc%253ALazadaMainSrp%253Brn%253Aff788d62b03f498f73664c1eaa1d4daf%253Bregion%253Aph%253Bsku%253A3540020292_PH%253Bprice%253A301.39%253Bclient%253Adesktop%253Bsupplier_id%253A500256573541%253Bbiz_source%253Ah5_internal%253Bslot%253A0%253Butlog_bucket_id%253A470687%253Basc_category_id%253A24429%253Bitem_id%253A3540020292%253Bsku_id%253A18242546621%253Bshop_id%253A3827162&fastshipping=0&freeshipping=1&fs_ab=2&fuse_fs=&lang=en&location=Bulacan&price=301.39&priceCompare=skuId%3A18242546621%3Bsource%3Alazada-search-voucher%3Bsn%3Aff788d62b03f498f73664c1eaa1d4daf%3BunionTrace%3A21410bbf17079088340442603e627c%3BoriginPrice%3A30139%3BvoucherPrice%3A30139%3BdisplayPrice%3A30139%3BsinglePromotionId%3A900000023788495%3BsingleToolCode%3ApromPrice%3BvoucherPricePlugin%3A1%3BbuyerId%3A0%3Btimestamp%3A1707908834440&ratingscore=4.982521847690387&request_id=ff788d62b03f498f73664c1eaa1d4daf&review=1602&sale=5040&search=1&source=search&spm=a2o4l.searchlist.list.0&stock=1', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product_answer`
--

CREATE TABLE `product_answer` (
  `paID` int(11) NOT NULL,
  `prodID` int(11) NOT NULL,
  `answerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_answer`
--

INSERT INTO `product_answer` (`paID`, `prodID`, `answerID`) VALUES
(31, 23, 71),
(32, 23, 118),
(33, 21, 71),
(34, 21, 119),
(35, 22, 71),
(36, 22, 120),
(37, 24, 72),
(38, 24, 118),
(39, 25, 72),
(40, 25, 119),
(41, 29, 72),
(42, 29, 120),
(43, 31, 73),
(44, 31, 118),
(45, 32, 73),
(46, 32, 119),
(47, 33, 73),
(48, 33, 120),
(54, 39, 108),
(55, 39, 113),
(56, 39, 114),
(57, 41, 108),
(58, 41, 113),
(59, 41, 114),
(60, 40, 108),
(61, 40, 115),
(62, 44, 109),
(63, 42, 109),
(64, 45, 110),
(65, 46, 111),
(66, 44, 109),
(67, 42, 109),
(68, 45, 110),
(69, 46, 111);

-- --------------------------------------------------------

--
-- Table structure for table `question_answer`
--

CREATE TABLE `question_answer` (
  `qaID` int(11) NOT NULL,
  `pqID` int(11) DEFAULT NULL,
  `bqID` int(11) DEFAULT NULL,
  `answerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_answer`
--

INSERT INTO `question_answer` (`qaID`, `pqID`, `bqID`, `answerID`) VALUES
(40, 43, NULL, 59),
(41, 43, NULL, 60),
(42, 43, NULL, 61),
(43, 43, NULL, 62),
(44, 44, NULL, 63),
(45, 44, NULL, 64),
(46, 44, NULL, 65),
(47, 44, NULL, 66),
(48, 45, NULL, 67),
(49, 45, NULL, 68),
(50, 45, NULL, 69),
(51, 45, NULL, 70),
(52, 46, NULL, 71),
(53, 46, NULL, 72),
(54, 46, NULL, 73),
(66, 52, NULL, 85),
(67, 52, NULL, 86),
(68, 52, NULL, 87),
(69, 53, NULL, 88),
(70, 53, NULL, 89),
(71, 54, NULL, 90),
(72, 54, NULL, 91),
(73, 55, NULL, 92),
(74, 55, NULL, 93),
(75, 56, NULL, 94),
(76, 56, NULL, 95),
(77, 56, NULL, 96),
(78, 57, NULL, 97),
(79, 57, NULL, 98),
(80, 57, NULL, 99),
(81, 57, NULL, 100),
(82, 58, NULL, 101),
(83, 58, NULL, 102),
(84, 58, NULL, 103),
(85, 58, NULL, 104),
(86, 59, NULL, 105),
(87, 59, NULL, 106),
(88, 59, NULL, 107),
(89, 60, NULL, 108),
(90, 60, NULL, 109),
(91, 60, NULL, 110),
(92, 60, NULL, 111),
(94, 62, NULL, 113),
(95, 62, NULL, 114),
(96, 62, NULL, 115),
(99, 47, NULL, 118),
(100, 47, NULL, 119),
(101, 47, NULL, 120),
(103, NULL, 1, 122),
(104, NULL, 1, 123),
(105, NULL, 1, 124);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `firstName`, `lastName`, `username`, `password`) VALUES
(1, 'Ash', 'Ketchum', 'admin', 'admin'),
(2, 'Hello', 'World', 'test1', 'test1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerID`);

--
-- Indexes for table `bonus_question`
--
ALTER TABLE `bonus_question`
  ADD PRIMARY KEY (`bqID`),
  ADD KEY `bq_fk1` (`categoryID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `parent_question`
--
ALTER TABLE `parent_question`
  ADD PRIMARY KEY (`pqID`),
  ADD KEY `pq_fk1` (`categoryID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prodID`),
  ADD KEY `product_fk1` (`categoryID`);

--
-- Indexes for table `product_answer`
--
ALTER TABLE `product_answer`
  ADD PRIMARY KEY (`paID`),
  ADD KEY `pa_fk1` (`prodID`),
  ADD KEY `pa_fk2` (`answerID`);

--
-- Indexes for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD PRIMARY KEY (`qaID`),
  ADD KEY `qa_fk1` (`pqID`),
  ADD KEY `qa_fk3` (`answerID`),
  ADD KEY `qa_fk4` (`bqID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `bonus_question`
--
ALTER TABLE `bonus_question`
  MODIFY `bqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parent_question`
--
ALTER TABLE `parent_question`
  MODIFY `pqID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `prodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `product_answer`
--
ALTER TABLE `product_answer`
  MODIFY `paID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `question_answer`
--
ALTER TABLE `question_answer`
  MODIFY `qaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bonus_question`
--
ALTER TABLE `bonus_question`
  ADD CONSTRAINT `bq_fk1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parent_question`
--
ALTER TABLE `parent_question`
  ADD CONSTRAINT `pq_fk1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_fk1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_answer`
--
ALTER TABLE `product_answer`
  ADD CONSTRAINT `pa_fk1` FOREIGN KEY (`prodID`) REFERENCES `product` (`prodID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pa_fk2` FOREIGN KEY (`answerID`) REFERENCES `answer` (`answerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question_answer`
--
ALTER TABLE `question_answer`
  ADD CONSTRAINT `qa_fk1` FOREIGN KEY (`pqID`) REFERENCES `parent_question` (`pqID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `qa_fk3` FOREIGN KEY (`answerID`) REFERENCES `answer` (`answerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `qa_fk4` FOREIGN KEY (`bqID`) REFERENCES `bonus_question` (`bqID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
