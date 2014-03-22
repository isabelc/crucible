Crucible
========

I am currently under construction. Soon, I will be a starter theme built on an efficient, albeit custom, framework. 

Come back soon.

Provisional Notes
-----------------

The first thing you want to do is copy the `crucible` directory and change the name. Then do a 6-step search and replace:

1. Search for `'crucible'` (inside single quotations) to capture the text domain and replace with: `'megatherium'`

2. Search for `crucible_` to capture all the function names and replace with: `megatherium_`

3. Search for `Text Domain: crucible` in style.css and replace with: `Text Domain: megatherium`

4. Search for <code>&nbsp;crucible</code> (with a space before it) to capture DocBlocks and replace with: <code>&nbsp;Megatherium</code> (with space before it and capital letter)

5. Search for `crucible-` to capture prefixed handles and replace with: `megatherium-`


Then, update the stylesheet header in style.css and the links in footer.php with your own information. Next, update or delete this readme. Change the crucible.pot filename to your textdomain.pot


