use jawnho_xyz;

SELECT substring_index(substring_index(movie_basic_copy1.movie_type,',', b.help_topic_id + 1), ',', -1),
movie_basic_copy1.movie_name FROM movie_basic_copy1 left join mysql.help_topic b ON b.help_topic_id < 
(LENGTH(movie_basic_copy1.movie_type) - LENGTH(REPLACE(movie_basic_copy1.movie_type, ',', '')) + 1);