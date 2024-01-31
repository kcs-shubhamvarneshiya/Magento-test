1.  Install prerequisites

    1. Docker for [Windows](https://docs.docker.com/docker-for-windows/install/ "https://docs.docker.com/docker-for-windows/install/"), [Mac](https://docs.docker.com/docker-for-mac/install/ "https://docs.docker.com/docker-for-mac/install/"), or [Linux](https://docs.docker.com/install/linux/docker-ce/ubuntu/#set-up-the-repository "https://docs.docker.com/install/linux/docker-ce/ubuntu/#set-up-the-repository").  You'll also need  to separately install [Docker Compose](https://docs.docker.com/compose/install/#install-compose "https://docs.docker.com/compose/install/#install-compose") if you're on Linux. Recommended minimum resource settings for Docker are:
        - CPUs: 8 Cores
        - Memory: 16GB
        - Swap: 2GB
        - Virtual disk limit: 128GB
        <br>
        <br>


    2. Github CLI https://github.com/cli/cli#installation. You will also need to login to the Github CLI https://cli.github.com/manual/gh_auth_login.
    
2.  Checkout Visual Comfort Adobe Commerce code from the Github repository.
    
3.  Confirm your host system contains your OpenSSH compatible SSH keys (id\_rsa/id\_rsa.pub).  On Windows, these keys need to be in C:/Users/<username>/.ssh.  On Mac and Linux they need to be in /home/<username>/.ssh.  the entire .ssh directory will be mounted into the container so any config and alternate keys will also be available.
            
4.  In a terminal/cmd window, do the following:
    
    1.  Navigate to the docker folder within the code directory at **docker/local**.
        
    2.  Run setup script **./bin/setup**. This will take a while to build.
        
        1.  Options for setup are:
            
            1.  `--with-sync` this will set it up to use synced volume mounts which improves performance on Mac OS setups
                
            2.  `--no-cache` this will not use any local docker cache
        
5.  Update hosts file for your platform with entries for the local domain of the site.  This domain will need to end with [lcgdocker.com](http://lcgdocker.com/ "http://lcgdocker.com") to ensure the built-in HTTPS certificate works and that you get the correct vhost configuration.
    
    1.  On Windows, the hosts file is located at C:\\Windows\\System32\\drivers\\etc\\hosts
        
    2.  On Mac and Linux, the hosts file is located at /etc/hosts
        
    3.  For example, a typical entry would look like **127.0.0.1 circa.lcgdocker.com**. Replace circa.lcgdocker.com with the local domain used.
        
6.  Use your web browser to navigate to the local URL as entered in the hosts file above.  The site should load at this point, if not refer to the [Troubleshooting](https://lyonscg.atlassian.net/wiki/spaces/CRL/pages/3172794579/Magento+2+-+Local+Setup+with+Client+Code#Troubleshooting "#Troubleshooting") section.
    
7.  Once site is confirmed operational, you'll may notice the mega menu styling isn't right. To fix this you'll need to generate the menu CSS as follows:
    
    1.  Access the admin at [https://circa.lcgdocker.com/admin](https://circa.lcgdocker.com/admin "https://circa.lcgdocker.com/admin")
        
    2.  Navigate to Stores > Configuration > Rootways Extensions > Mega Menu
        
    3.  Click Save Config
        
    4.  Flush all caches