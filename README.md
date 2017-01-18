# breathalyzer
Calculates Levenshtein distances sum.
<h2>Usage</h2>
<pre>
$ php breathalyzer.php 187
</pre>
Or:
<pre>
$ php breathalyzer.php example_input
</pre>

I have been using the procedural approach with this task to minimize script working time.
<h2>Results (file '187'):</h2>
<ul>
<li>Windows 10 64 bits, Core i5, php 5.6: ~2 sec</li>
<li>Windows 10 64 bits, Core i5, php 7.0: ~1.8 sec</li>
<li>Ubuntu 16.10 64 bits, Core i3, php 7.0: ~1.35 sec</li>
</ul>