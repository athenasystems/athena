Athena CE - Community Edition
========
**Athena Business Software**  
A simple framework for business software, designed to be run on the LAMP stack, written in basic PHP (with a tiny bit of Perl), aimed at PHP programmers and business managers who wish to build their own bespoke system.

# What you get
It sets up 5 subdomains for Staff, Management, Customers and Suppliers, and a WWW for Help and to log in from. It gives you two VERY basic modules, Quotes and Invoices.

# Why is it blank?
The idea here is that no two businesses are the same, and to get good bespoke software you need to replicate the existing business practices and conventions. Since each is different, it's best to start with a blank template and build it up from there. This is such a template.

# Development Cycle
It is suggested that you set up a development version on a Debian based Linux desktop OS, and run the live version on a non GUI Debian based distro. The installation script below will set up either of these environments.

# Installation
Do the following as root, or via sudo :-

wget -N https://raw.githubusercontent.com/athenasystems/athena/master/athena-setup && bash athena-setup  