# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: https://doc.scrapy.org/en/latest/topics/item-pipeline.html

import pymysql.cursors
from movie.items import MovieBasicItem, MovieBoxItem, PerformersItem

class MoviePipeline(object):
    def __init__(self):
        # 连接数据库
        self.connect = pymysql.connect(
            host='47.107.247.85',  # 数据库地址
            port=3306,  # 数据库端口
            db='jawnho_xyz',  # 数据库名
            user='jawnho_xyz',  # 数据库用户名
            passwd='f3759682a885ba3d',  # 数据库密码
            charset='utf8',  # 编码方式
            use_unicode=True)
        # 通过cursor执行增删查改
        self.cursor = self.connect.cursor();

    def process_item(self, item, spider):
        self.__init__()
        # 传递过来的item是什么类型，就调用该类型中对应的save函数
        item.save(self.cursor)
        self.connect.commit()
        # 关闭数据库
        self.close_sql()

    def close_sql(self):
        self.cursor.close()
        self.connect.close()


