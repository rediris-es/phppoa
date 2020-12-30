#phpPoA 2.0

##Contents of the distribution:

README                This file
GPL                   Text of the GNU General Public License, applicable to all the software
LICENSE               Copyright information and short text of the license, including responsibility disclaimer
doc                   Documentation for this distribution
doc/api               API documentation for developers
doc/tutorials         User tutorials to demonstrate the use of the library
lib                   Auxiliary classes that implement most of the functionality that is shipped with this distribution
lib/authn             Authentication engines
lib/authz             Authorization engines
lib/crypto            Cryptographic classes
lib/db                Database classes
lib/third-party       Third party libraries
messages              Support files for message internationalization
samples               Sample usage files, including configuration
tools                 Useful tools that ship with the library

PoA.php               The main class of phpPoA2. Include only this file in your applications
AutoPoA.php           PoA class that automatically redirects on error
LitePoA.php           PoA class with backwards compatibility
PoAConfigurator.php   The main configuration class
PoAEventHandler.php   The main event handler used by phppoA2
PoAException.php      Generic phpPoA exception implementation
PoALog.php            Logging class
PoAUtils.php          Utility class
definitions.php       Useful constants
