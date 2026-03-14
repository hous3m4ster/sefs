# Stupidly-Easy-File-Server
An easy program that spins up a local webserver for easy local file sharing

# Warning
This application is only intended to be used locally, with users you trust. Do not use this application in production environments, where threat actors have access to your machine. Every user can be a threat actor, and should be treated as such. I am in no way liable for any damage caused to your, or other's, machine due to your own negligence. 

## Installation
Clone the repo to your local machine
Install the PHP package.

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

## Note

On many modern distros a firewall is automatically installed. This is the case for at least, but not limited to: Fedora, Rocky linux, Alma linux and other RHEL based distros. When a firewall is active on your system, please add port 3434/tcp to the allowed ports. Using firewalld, which is the utility used by RHEL based systems and Fedora, the following command will achieve this:
```
sudo firewall-cmd --add-port=3434/tcp
```
This will add the required port to the allowed ports temporarily. On next reboot, the port will be removed from the firewall.
