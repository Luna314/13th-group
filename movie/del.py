import pymysql.cursors
import pymysql
import time

db = pymysql.connect = pymysql.connect(
            host='47.107.247.85',  # 数据库地址
            port=3306,  # 数据库端口
            db='jawnho_xyz',  # 数据库名
            user='jawnho_xyz',  # 数据库用户名
            passwd='f3759682a885ba3d',  # 数据库密码
            charset='utf8',  # 编码方式
            use_unicode=True)

cursor = db.cursor()

sql = "TRUNCATE table Images_copy1"
#t = (1312312)
#print(t)
cursor.execute(sql)
db.close()
