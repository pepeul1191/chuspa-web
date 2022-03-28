#!/usr/bin/env python
# -*- coding: utf-8 -*-

from flask import Blueprint, render_template

view = Blueprint('admin_bludprint', __name__)

@view.route('/', methods=['GET'])
def home():
    return 'hola mundo', 200