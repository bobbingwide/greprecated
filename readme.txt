=== greprecated ===
Contributors: bobbingwide
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: search, grep, summary, count
Requires at least: 4.8.2
Tested up to: 4.8.2
Stable tag: 0.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Search for and count calls to selected PHP functions producing details and a summary table.


greprecated is being used to measure the status of the work to internationalize the oik base plugin and its dependent plugins.

- For a selected set of functions it counts the PHP function or static method calls in the source code.
- It ignores Git files.
- It ignores non-PHP files.
- It uses an input file to list the functions to search for.
- Matching is case insensitive.
- The output file contains a simple CSV summary of the number of calls.

This summary output is them manipulated into merged CSV file showing the number of calls to the deprecated functions 
and the number converted to the replacement functions.

From this we can determine the overall status of the project.

Date   | Converted | Deprecated | Total | % Complete
------ | --------- | ---------- | ----- | ----------
22 Mar |	0        | 710       |	710   |	0.00%
31 Jul |	172	     | 587        |	759   |	22.66%
30 Aug |	218	     | 551        | 769	  | 28.35%
25 Sep |	275	     | 496        | 771   |	35.67%


== Installation ==
1. Install as if it were a WordPress plugin but do not activate.
1. Run under oik-batch.

== Frequently Asked Questions ==
=== When will the project complete? ===
When the % Complete figure is close to 100%
We will need to allow for a certain number of Deprecated calls.
These will be false positives since the deprecated code will still exist, but won't be called.

=== Are PHPUnit tests searched? ===
Yes, currently.

=== How do you run it? ===
I use a simple batch file, called fg.bat, which performs

1. Edit the input file flh0grep.tab
2. Run greprecated.php under oik-batch
3. Edit the generated file flh0grep.txt


=== Dependencies? === 

- greprecated.php is a batch routine, run under oik-batch
- It is dependent upon oik's library functions

=== Are there similar routines? ===

- greprecated.php started life as a REXX exec called flh0grep.rex.
- Its original description was "Tabulate usage of certain keywords in the source files".
- I started writing it over 25 years ago, running on OS/2.
- In 1998 I changed it to run under Windows '95. 
- In 2000/2001 it was forked to automatically measure the progress of a migration project from OS/2 to Windows.
- I stopped using it 5 years ago, when I uninstalled REXX.
- About that time I started using PhpStorm, but never seriously.


=== Integrated with PhpStorm? ===
Q. How can you achieve something similar with PhpStorm?
A. I don't know

- So I have no idea how to use PhpStorm to do what greprecated does.
- Which is why it's taken me 5 month so consider automatically measuring the status of the internationalization project.

=== Why not use a PHP Parser? ===
Q. Would it not be better to use a PHP Parser for this?
A. Yes. Almost certainly.

That's why the code is so quick and dirty.


== Screenshots ==
1. greprecated in action

== Upgrade Notice ==
= 0.0.0 =
Bespoke routine used to measure the status of internationalization of oik plugins.

== Changelog == 
= 0.0.0 =
* Added: New routine using ideas from a very old REXX exec.

