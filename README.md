Crucible
========

Crucible is currently under construction and not ready for public use. Soon, this will be a starter theme built on a custom, yet very efficient, framework.

Notes
-----

The first thing to do is copy the `crucible` directory and change the name. Then do all of these instances of search and replace:

1. Search for `'crucible'` (inside single quotations) to capture the text domain and replace with: `'megatherium'`

2. Search for `admin.php?page=crucible` and replace with `admin.php?page=mega-therium` with dashes to match exactly the themeslug.

3. Search for `crucible_` to capture all the function names and replace with: `megatherium_`

4. Search for `Text Domain: crucible` in style.css and replace with: `Text Domain: megatherium`

5. Search for <code>&nbsp;Crucible_</code> (with a space before it and capital letter) to capture class name prefixes and replace with: <code>&nbsp;Megatherium_</code> (with space before it and capital letter)

6. Search for <code>&nbsp;Crucible</code> (with a space before it) to capture DocBlocks  and replace with: <code>&nbsp;Megatherium</code> (with space before it and capital letter)

7. Search for `crucible-` to capture prefixed handles and replace with: `megatherium-`

8. Search for `_CRUCIBLE_` in all caps to capture the storename constant for EDD SL and replace with `_MEGATHERIUM_`

9. Search for `page=crucible` and replace with `page=megatherium`

10. Then, update the stylesheet header in style.css and the links in footer.php with your own information. 

11. Change the crucible.pot filename to your textdomain.pot

12. Next, update or delete this readme. 
