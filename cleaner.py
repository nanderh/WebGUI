#!/usr/bin/env python
import sys
import time
import subprocess

delay = sys.argv[1]


while (True):
    time.sleep(int(delay))
    subprocess.call("python parselog.py", shell=True)
    subprocess.call("echo '' > sslstrip.log", shell=True)