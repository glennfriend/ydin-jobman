## For developer

### clone your package
```
git clone xxxxxxxxxx "packages/__ydin-jobman"
```

### Package Development
```
composer remove "ydin/jobman"
composer config "repositories.jobman" path "packages/__ydin-jobman"
composer require "ydin/jobman:dev-master"
```

### git tag push
```
git tag 0.1.0  --force
git push origin --tags  --force
```

### update packagist.org
```
update to https://packagist.org/packages/ydin/jobman (or hook)
```

### install from composer
```
composer remove "ydin/jobman"
composer config "repositories.jobman" path ""
composer require "ydin/jobman:0.1.0"
```
