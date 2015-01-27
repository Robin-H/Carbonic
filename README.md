# Carbonic
Simple PHP framework

Use it to your likings, I found other frameworks to be usually blown way out of proportions or have extremely complicated OOP 'implementations'.

Structure?
Create your project folder and add the folders: app, cache & carbonic. Link carbonic to this repo (or don't) and build inside 'app' using model / view / controller folders.

Requirements? A recent version of PHP (5.3+), the model uses PDO and MySQL. Maybe I'll add other DB engines as a possibility later.

Make sure the defaultController in carbonic/Config.php exists. I do not provide a controller with this package, simply because I want to be able to link this repo and all app-specific code should be outside the carbonic-folder.

I'll try to create a sample app with carbonic as its core. If I haven't done so by January 2016, don't expect it to come.
