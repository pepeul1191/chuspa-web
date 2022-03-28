# !/usr/bin/env python
# -*- coding: utf-8 -*-

import os
from os.path import join, dirname
from dotenv import load_dotenv

dotenv_path = join(dirname(__file__), '.env')
load_dotenv(dotenv_path)

print('1 ++++++++++++++++++++++++++++++++++')
print(os.environ.get("DB"))
print('2 ++++++++++++++++++++++++++++++++++')

CONSTANTS = {
    'secret_key': 'mysercretkey',
    'base_url': 'http://localhost:3000/',
    'static_url': 'http://localhost:3000/',
}