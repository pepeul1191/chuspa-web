#!/usr/bin/env python
# -*- coding: utf-8 -*-

#from access.views import view as access_view
#from access.views2 import view as access_view2
from website.views import view as site_view

def register(APP):
    # register blueprints
    #APP.register_blueprint(access_view)
    #APP.register_blueprint(access_view2)
    APP.register_blueprint(site_view)