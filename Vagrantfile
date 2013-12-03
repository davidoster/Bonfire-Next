# -*- mode: ruby -*-
# vi: set ft=ruby :

# ------------------------------------------------------------
# Originally based on https://github.com/Divi/VagrantBootstrap
# ------------------------------------------------------------

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    # Core configurations
    # -------------------
    config.vm.box = "precise32"
    config.vm.box_url = "http://files.vagrantup.com/precise32.box"
    config.ssh.forward_agent = true

    config.vm.provider :virtualbox do |v|
        v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        v.customize ["modifyvm", :id, "--memory", 512]
        v.customize ["modifyvm", :id, "--name", "My Box"]
    end

    # Running bootstrap
    # -----------------
    config.vm.provision :shell, :path => "vagrant_bootstrap/bootstrap.sh"

    # Forwarding Ports
    # ----------------
    config.vm.network :forwarded_port, guest: 80, host: 8000
    config.vm.network :forwarded_port, guest: 3306, host: 33060
end