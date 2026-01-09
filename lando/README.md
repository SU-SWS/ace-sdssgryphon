# Setup Local Environment - Lando
**2025-12-19:** Lando is currently unsupported. The migration from BLT to sws-drush-commands broke a few setup steps, which need to be resolved. 

You can set up this stack as a complete development environment using nothing more than Docker and Lando installed in your linux-like environment.

### Prerequisites

#### Linux/MacOS
1. Set up Docker on your distro of choice.  Instructions for installing Docker in linux [can be found here](https://docs.docker.com/desktop/linux/install/), and these are [the MacOS instructions.](https://docs.docker.com/desktop/mac/install/)
2. Set up Lando on your distro of choice.  Instructions for installing Lando in linux [can be found here](https://docs.lando.dev/getting-started/installation.html#linux), and these are [the MacOS instructions.](https://docs.lando.dev/getting-started/installation.html#macos)

#### Windows/WSL
Because Docker works best with Windows Subsystem for Linux V.2, we suggest you proceed that way.
1. [Install Windows Subsystem for Linux V.2](https://docs.microsoft.com/en-us/windows/wsl/install)
2. [Install Docker Desktop for Windows, and enable the WSL2 extensions.](https://docs.docker.com/desktop/windows/wsl/)
3. [Install Docker for linux in your WSL2 environment.](https://docs.docker.com/desktop/linux/install/ubuntu/)
4. Install Lando for linux in your WSL2 environment.  [Instructions can be found here.](https://docs.lando.dev/getting-started/installation.html#linux)

No other prerequisites are necessary, though you may find it helpful to have PHP 8.1+, Composer 2, and Git installed locally on your system.

### Installation
1. Clone this repo to a convenient place in your environment, and change directories to the location to which you cloned it.
2. In the repo root, run `./lando/setup_lando.sh`.
