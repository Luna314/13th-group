import re
import requests
from fontTools.ttLib import TTFont
from scrapy import Spider
from scrapy.http import Request
from movie.items import MovieBasicItem, MovieBoxItem, PerformersItem
import copy
import base64
import time
import os
from itertools import chain

# 获取对应关系
def get_dict():
    font = "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"
    fontdata = base64.b64decode(font)
    file = open('./1.woff', 'wb')
    file.write(fontdata)
    file.close()
    online_fonts = TTFont('./1.woff')
    #online_fonts.saveXML("text.xml")
    font_dict = dict()

    base_num = {
        "uniE6CD": "2",
        "uniE1F5": "4",
        "uniEF24": "3",
        "uniEA4D": "1",
        "uniF807": "5",
        "uniEF10": "6",
        "uniE118": "7",
        "uniE4F5": "8",
        "uniECFD": "9",
        "uniF38B": "0"
    }
    _data = online_fonts.getGlyphSet()._glyphs.glyphs
    for k, v in base_num.items():
        font_dict[_data[k].data] = v
    return font_dict


class MaoYan(Spider):
    name = 'movie_spider'

    def __init__(self):
        self.font_dict = get_dict()
        super(MaoYan, self).__init__()

    def start_requests(self):
        start_url = 'https://piaofang.maoyan.com/?ver=normal'
        yield Request(start_url, dont_filter=True, encoding='utf-8', callback=self.get_url)

    def get_url(self, response):
        body = response.text
        movielist = re.findall('<ul class=\"canTouch\" data-com=\"hrefTo,href:\'/movie/(\d+)\?_v_=yes\'\"', body)
        for i in movielist:
            for url1 in ['https://piaofang.maoyan.com/movie/{}'.format(i)]:
                yield Request(url1, dont_filter=True, meta={'next_url': i}, encoding='utf-8', callback=self.parse_basic)
    #获取基本信息
    def parse_basic(self, response):
        item = MovieBasicItem()
        new = response.meta['next_url']
        url2 = 'https://piaofang.maoyan.com/movie/{}/celebritylist'.format(new)
        body = response.text
        item['movie_name'] = re.findall("<span class=\"info-title-content\">([\s\S]+?)</span>", body)
        item['movie_type'] = re.findall("<div class=\"detail-list-content\">\s+?<p class=\"info-category\">\s+([\s\S]+?)\n", body)
        item['release_date'] = re.findall("<span class=\"score-info ellipsis-1\">([\s\S]+?)大陆上映</span>", body)

        yield Request(url2, dont_filter=True, meta={'item': copy.deepcopy(item), 'next_url': new, 'moviename': item['movie_name'], 'releasedate': item['release_date']}, encoding='utf-8', callback=self.parse_stuff)
    #获取演职人员
    def parse_stuff(self, response):
        item = response.meta['item']
        moviename = response.meta['moviename']
        releasedate = response.meta['releasedate']
        new = response.meta['next_url']
        url3 = 'https://piaofang.maoyan.com/movie/{}/boxshow'.format(new)
        body = response.text
        item['director'] = re.findall("<p class=\"p-item-name ellipsis-1\">([\s\S]+?)</p>", body)[0]
        performer_ids = re.findall("<a href=\"/celebrity/\d+\" class=\"p-link\" data-id=\"(\d+)\"", body)[1:11]
        for i in performer_ids:
            url4 = 'https://piaofang.maoyan.com/celebrity?id={}'.format(i)
            yield Request(url4, dont_filter=True, meta={'moviename': moviename, 'releasedate': releasedate}, encoding='utf-8', callback=self.parse_gender)
        yield Request(url3, dont_filter=True, meta={'moviename': moviename, 'item': item}, encoding='utf-8', callback=self.parse_box)
        yield item
    #获取演职人员性别
    def parse_gender(self, response):
        item = PerformersItem()
        item['movie_name'] = response.meta['moviename']
        item['release_date'] = response.meta['releasedate']
        #performer_info = response.meta['performer']
        body = response.text
        item['performer'] = re.findall("<p class=\"cname\">([\s\S]+?)</p>", body)
        item['gender'] = re.findall("<span class=\"name\">性别：</span>\s+?<span>([\s\S]+?)</span>", body)
        #item['performers'] = dict(zip(performer, gender))
        #item['performers'].append(performer_info)
        return item

    #获取票房
    def parse_box(self, response):
        item = MovieBoxItem()
        item1 = response.meta['item']
        body = response.text
        item['movie_name'] = response.meta['moviename']
        item1['total_box'] = re.findall("<span class=\"boxing\">累计票房([\s\S]+?)万</span>", body)
        movie_dates_get = re.findall("<span><b>([\s\S]+?)</b>|<span style=\"color:#ea4742\"><b>([\s\S]+?)</b>", body)
        movie_date_get1 = list(chain(*movie_dates_get))
        movie_dates = [i for i in movie_date_get1 if i != '']
        movie_box = re.findall("<div class=\"t-row\">\s+<div class=\"t-col\"><i class=\"cs\">([\s\S]+?)</i>", body)
        #del(movie_box[0])
        data_woff = get_woff(body)
        box_proportions = list()
        for i in movie_box:
            box_proportions.append(format_num(i, self.font_dict, data_woff))
        movie_box = box_proportions
        item['box_date'] = movie_dates
        item['box_office'] = movie_box
        return item, item1

def get_woff(body):
    file_name = '2.woff'
    font = re.findall("charset=utf-8;base64,([\s\S]+)\) format\(\"woff\"\)", body)
    if font:
        font = font[0]
        fontdata = base64.b64decode(font)
        file = open(file_name, 'wb')
        file.write(fontdata)
        file.close()
    online_fonts = TTFont("2.woff")
    data = online_fonts.getGlyphSet()._glyphs.glyphs
    return data


def format_num(string, font_dict, data_woff):
    if str(string).endswith("万") or str(string).endswith("%") or str(string).endswith("亿"):
        unit = string[-1]
        string = string.replace("万", '').replace("%", "").replace("亿", "")
        num_list = string.split(";")
        num = list()
        for i in num_list:
            if not i.startswith("."):
                i = i[3:].upper()
                if i:
                    i = font_dict[data_woff["uni%s" % i].data]
                    num.append(i)
            else:
                num.append(".")
                i = i[4:].upper()
                i = font_dict[data_woff["uni%s" % i].data]
                num.append(i)
        num.append(unit)
        return "".join(num)
    elif str(string) != '--':
        num_list = string.split(";")
        num = list()
        for i in num_list:
            if i and not i.startswith("."):
                i = i[3:].upper()
                i = font_dict[data_woff["uni%s" % i].data]
                num.append(i)
            elif i:
                num.append(".")
                i = i[4:].upper()
                i = font_dict[data_woff["uni%s" % i].data]
                num.append(i)
        return "".join(num)
    else:
        return '0.0'
