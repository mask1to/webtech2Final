ALTER TABLE
  question DROP FOREIGN KEY question_ibfk_1;
ALTER TABLE
  questionOption DROP FOREIGN KEY questionOption_ibfk_1;
ALTER TABLE
  `question`
ADD
  FOREIGN KEY (`test_id`) REFERENCES `test`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE
  `questionOption`
ADD
  FOREIGN KEY (`question_id`) REFERENCES `question`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;