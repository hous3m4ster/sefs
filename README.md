# Stupidly-Easy-File-Server
An easy program that spins up a local webserver for easy local file sharing

# Warning
This application is only intended to be used locally, with users you trust. Do not use this application in production environments, where threat actors have access to your machine. Every user can be a threat actor, and should be treated as such. I am in no way liable for any damage caused to your, or other's, machine due to your own negligence. 

## Installation
Clone the repo to your local machine
Install the PHP package. Examples:
Fedora:
```
sudo dnf install php
```

Ubuntu/Debian/Linux Mint:
```
sudo apt install php
```

Arch:
```
sudo pacman install php
```

After installing php, run the startup file in the main directory of the project. When this is done, a new webserver is spun up at [your local ip]:3434.
You can find your local ip with 
```ip addr``` 