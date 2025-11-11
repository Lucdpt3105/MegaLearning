@echo off
cd /d %~dp0
node src/database/seeders/index.js
