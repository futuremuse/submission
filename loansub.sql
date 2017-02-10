CREATE TABLE IF NOT EXISTS `loanrecords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loanamt` int(11) NOT NULL,
  `propvalue` int(11) NOT NULL,
  `ssn` varchar(9) NOT NULL,
  `loanId` varchar(10) NULL,
  `acceptance` varchar(8) NOT NULL,
  `lastMod` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

