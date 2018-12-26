# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# https://doc.scrapy.org/en/latest/topics/items.html

import scrapy
import pymysql

class MovieBasicItem(scrapy.Item):
    movie_name = scrapy.Field()
    movie_type = scrapy.Field()
    director = scrapy.Field()
    release_date = scrapy.Field()
    total_box = scrapy.Field()

    def save(self, cursor):
        sql = "INSERT IGNORE INTO movie_basic (movie_name, movie_type, director, release_date, total_box) VALUES (%s, %s, %s, %s, %s)"
        t = (self['movie_name'], self['movie_type'], self['director'], self['release_date'], self['total_box'])
        cursor.execute(sql, t)


class MovieBoxItem(scrapy.Item):
    movie_name = scrapy.Field()
    box_date = scrapy.Field()
    box_office = scrapy.Field()

    def save(self, cursor):
        create_sql = "CREATE TABLE IF NOT EXISTS {} (box_date DATE, box_office FLOAT, PRIMARY KEY(box_date)) DEFAULT CHARACTER SET utf8;".format(''.join(self['movie_name']))
        #k = pymysql.escape_string(''.join(self['movie_name']))
        cursor.execute(create_sql)
        for i in range(0, len(self['box_date'])):
            sql = "INSERT IGNORE INTO {} (box_date, box_office) VALUES (%s, %s)".format(''.join(self['movie_name']))
            t1 = (self['box_date'][i], self['box_office'][i])
            cursor.execute(sql, t1)
            #update_sql = "UPDATE {} SET box_office=%s WHERE box_date=%s".format(''.join(self['movie_name']))
            #t2 = (self['box_office'][i], self['box_date'][i])
            #cursor.execute(update_sql, t2)


class PerformersItem(scrapy.Item):
    movie_name = scrapy.Field()
    performer = scrapy.Field()
    gender = scrapy.Field()
    release_date = scrapy.Field()

    def save(self, cursor):
        sql = "INSERT IGNORE INTO performers(movie_name, release_date, performer, gender) VALUES (%s, %s, %s, %s)"
        t = (self['movie_name'], self['release_date'], self['performer'], self['gender'])
        cursor.execute(sql, t)
