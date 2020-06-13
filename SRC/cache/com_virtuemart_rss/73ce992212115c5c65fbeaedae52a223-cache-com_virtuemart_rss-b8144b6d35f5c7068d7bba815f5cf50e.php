<?php die("Access Denied"); ?>#x#a:2:{s:6:"result";a:5:{i:0;O:8:"stdClass":3:{s:4:"link";s:84:"https://virtuemart.net/news/500-release-of-virtuemart-3-8-and-covid-support-campaign";s:5:"title";s:52:"Release of VirtueMart 3.8 and Covid support campaign";s:11:"description";s:12683:"<p style="text-align: justify;">This is a special release in these unusual times. It was planned as simple version with an optimisation boost and bugfixes for VM 3.6.10, but it became a lot more than that. Many people are affected by a corona stasis. One of our members had to go in quarantine (without being infected) where he had a lot of time tor develop and donate smaller enhancements. Personally, I had been preparing for coming restrictions since the end of February since it was clear that Germany will follow the other countries. My wife and I are now taking care of our children's homework and home-education, which are aged from 3 to 11 and my development time is severely impacted. On the other hand this period allowed to really hard-cook this version in the debug process. It is already in use on some live shops for at least the past 2 weeks now.</p>
<p>Some of our developers also joined the <span style="font-size: 12pt;">COVID Support campaign for the Joomla community</span> <a href="https://covid.joomlart.com/" target="_blank" rel="noopener">https://covid.joomlart.com/</a>. Participating developers offer a <span style="font-size: 12pt;">20% discount</span> on <a href="https://extensions.virtuemart.net" target="_blank" rel="noopener">extensions.virtuemart.net</a>&nbsp;</p>
<p><img src="https://virtuemart.net/images/stories/news/owl-2920403_640.jpg" alt="VirtueMart Version Eagle Owl" /><br /><span>Image by <a href="https://pixabay.com/users/Alexas_Fotos-686414/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2920403">Alexas_Fotos</a> from <a href="https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2920403">Pixabay</a></span></p>
<p style="text-align: justify;">The biggest change under the hood are the new optimizations that avoid massive sql requests and use booleans to decide whether we need to load data from an xref table. The technique has some implications, which are described on docs.virtuemart.net <a href="http://docs.virtuemart.net/tutorials/development/241-reduced-database-access-by-using-booleans-for-reference-tables.html.">http://docs.virtuemart.net/tutorials/development/241-reduced-database-access-by-using-booleans-for-reference-tables.html.</a> When I worked on that pattern for categories, I noticed that we can enhance the loading of a category tree in a similar way. Rough tests with more than 200 categories (organised as years) indicate a 5 times faster loading time.</p>
<p style="text-align: justify;">The customfield_value created a permanent performance bottleneck. VirtueMart now creates database keys shortened to the first 50 values, which is a reasonable compromise between using a customfield_value as a simple information value and using it as a searchable value.</p>
<p style="text-align: justify;">The VirtueMart native Language Switcher, which is currently part of the VirtueMart membership <a href="https://extensions.virtuemart.net/support-updates/virtuemart-membership">https://extensions.virtuemart.net/support-updates/virtuemart-membership</a>, now works more more reliable after some really hard work on the router. The new language switcher creates any SEF Urls for different languages. This means it can switch between product details or just the account maintenance - of course without landing on the homepage. Maybe not that interesting for shoppers, but of importance for administrators. <a href="https://extensions.virtuemart.net/support-updates/virtuemart-membership"> </a><a href="https://extensions.virtuemart.net/support-updates/virtuemart-membership">https://extensions.virtuemart.net/support-updates/virtuemart-membership</a>.</p>
<p style="text-align: justify;">Template developers who used vmbeez3 before 3.8.0 must consider the security leak reported by the Joomla community <a href="https://developer.joomla.org/security-centre/803-20200302-core-xss-in-protostar-and-beez3.html">https://developer.joomla.org/security-centre/803-20200302-core-xss-in-protostar-and-beez3.html</a> and update their templates as soon as possible. It is just two lines.</p>
<p style="text-align: justify;">The next milestones are enhancing/fixing the VirtueMart order editing, invoice handling and Joomla 4 compatibility.</p>
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p>&nbsp;</p>
<h3>Optimisations</h3>
<ul>
<li>Using booleans to decide if the data of an xref table should be loaded - saving large SQL queries</li>
<li>Enhanced category tree building</li>
<li>Finally Final keys for customfield_value</li>
<li>added static to function getProductListing</li>
<li>if automaticSelectedPayment/Shipment is set to none, the whole trigger plgVmOnCheckAutomaticSelected is not longer executed</li>
<li>replaced overpowered md5 for hash replaced against crc32 or removed completly</li>
<li>renderVendorFullVendorList is cached now</li>
<li>increased size of vendor_terms_of_service to mediumtext</li>
<li>function getProductChildIds extended and cache added</li>
<li>customfield C uses now function getProductChildIds in the product model (cached)</li>
<li>smarter loading of parent category in backend category view</li>
</ul>
<h3>enhanced features for shop</h3>
<ul>
<li>Coupon handling enhancements - additions included from Creative Momentum Ltd</li>
<li>featured products load 3 times more than necessary to shuffle with PHP (Random per sql is not really random).</li>
<li>Snippet ld-json and double quote in product descriptions <a href="https://forum.virtuemart.net/index.php?topic=143951.0">https://forum.virtuemart.net/index.php?topic=143951.0</a></li>
<li>Revenue report: added date_presets for reporting over previous two years with totals available by year/month</li>
<li>FE managing link, add new product link, notify me link buttonized</li>
</ul>
<h3>enhanced features for templaters/developers</h3>
<ul>
<li>added loading of "searchable" also for the cart</li>
<li>enhanced recognition of frontend manager</li>
<li>tableupdater enhanced being more failproof by RuposTel</li>
<li>changed function isImage so, that it takes now a full url and not just the extension as input parameter. The parameter before was quite useless, because half of the work of the function is to get the file extension.</li>
<li>medihahandler - enhanced display of the options upload, replace</li>
<li>Standard payment has now also the method as variable in the layout post_payment</li>
<li>ensureUniqueId correctly implemented for radiolists for multivariants</li>
<li>added variable show_notify to product model based on the order min level (not just 1 as before)</li>
<li>vmprices, enhanced the lines catching the add to cart button</li>
<li>Added function resetEntireCart to cart</li>
</ul>
<h3>new features for shop</h3>
<ul>
<li>Colors for shipment methods in the admin order list</li>
<li>Added config options for sql - optimisation</li>
<li>Added config option to disable layout overrides</li>
<li>added tool reset_Has_x_Fields to updatesmigration view, which sets all "has_" fields to NULL</li>
<li>hidden config populateEmptyST (ShipTo details remain empty and do not get auto populate by the details in the BillTo details)</li>
<li>hidden config shoppergroupDontSaveCart - dont store carts for logged in users if in specified shoppergroups</li>
<li>Product model added hidden config changeCategoryRemoveFilter</li>
</ul>
<h3>new features for templaters/developers</h3>
<ul>
<li>Debug Option for router</li>
<li>vmAccess added function isFEmanager()</li>
<li>added extra override posssibility for FE manager views using the suffix Admin to the view name (in case BE and FE view has the same name)</li>
<li>Important feature for class vmtables function load, when the $overWriteLoadName is within the translateable fields, then the "where table" is the language table and not the main table. So we can now load a product by slug, for example.</li>
<li>added the trigger plgVmOnSendVmEmail in function sendVmMail</li>
<li>added javascript function sendFormChange to vm2admin.js, which sends the form of changed elements if using as class sendFormChange</li>
<li>Changed the vmtime so, that we can sum up the taken measurements</li>
</ul>
<h3>compatibility for Joomla, Joomla 4, PHP7.4</h3>
<ul>
<li>replaced $app-&gt;isAdmin/isSite() and similar with VmConfig::isSite() or VmConfig::isSiteByApp()</li>
<li>replaced more $obj-&gt;$value with $obj-&gt;{$value}</li>
<li>replaced class hasTip with "hasTooltip", furthermore added JHtml::_('bootstrap.tooltip'); for joomla native bootstrap tooltips</li>
</ul>
<h3>Fixes</h3>
<ul>
<li>fixed moving/ordering of categories</li>
<li>hidden configuration ChangeShopperDeleteCart fixed resetting of the cart if switching to a user or registering</li>
<li>Cart takes directly the email of logged in joomla users</li>
<li>Model customfields, customfields should now also work with 0 values <a href="http://forum.virtuemart.net/index.php?topic=142152.0">http://forum.virtuemart.net/index.php?topic=142152.0</a></li>
<li>Important fix for customfields, Removing a disabler from a customfield must delete the customfield which stores the disabler</li>
<li>usermodel storing user of vendors</li>
<li>fixed custom language fallbacks</li>
<li>vmTable fixes for language fallback, the problem was that the function is used itself twice so the set temporarly language got lost</li>
<li>country dropdown sorted by ordering and alphabetically and with special chars like äöü</li>
<li>added _noLimit = true; to prevent that the state of the model is used for Shipmentdropdown, category ordering dropdown,</li>
<li>removed old tasks in controllers for ajax, function viewJson</li>
<li>removed false positive "Set shoppergroup error"</li>
<li>removed task=viewJson&amp; from Urls</li>
<li>mediahandler replaced manual setup url against js variable medialink</li>
<li>fix in shopfunctions.php functions renderWeightUnitList and renderUnitIsoList, add unknown units to prevent that they are changed/deleted (thx RuposTel)</li>
<li>Rounding for currency conversion of costprice and of Margin in case "round only display" is unchecked.</li>
<li>product edit, unpublished categories were not rendered</li>
<li>cart max_order_level must be checked after checking for quantity steps (by RuposTel)</li>
<li>a bit different enhanced quantity check (by RuposTel)</li>
<li>Fixed naming of category parameters</li>
<li>Fix for usermodel function getUserList in case searchTable shoppers</li>
<li>html tag for radio buttons Multivariant fixed ids</li>
<li>replaced wrong Vmconfig ajax_category against jdynupdate</li>
</ul>
<h3>Router</h3>
<ul>
<li>Big update for multilanguage pages. Loads automatically different language if a language tag is given.</li>
<li>Debug option for router</li>
<li>Important fix to prevent error if there is no menu item set for the account maintenance</li>
<li>function getProductId loads the CategoryName with array_pop not end, the hash uses base64_encode instead of md5 (was overpowered)</li>
<li>added unset "start" to productdetails view (hardly used, pagination for related products?), but could generical create bad links with the language switcher</li>
<li>function getFieldOfObjectWithLangFallBack was joining language fallback tables, but not using fallbacks in the where clause</li>
</ul>
<h3>&nbsp;Security</h3>
<ul>
<li>html_entity_decode for order names in order list</li>
<li>When no safe path was given, it was corrected by JPath::clean() to the domain root path and created accidently safepath files and folders in the root directory, fixed.</li>
<li>vmbeez3 updated with joomla beez3 template and security update j3.9.16 <a href="https://developer.joomla.org/security-centre/803-20200302-core-xss-in-protostar-and-beez3.html">https://developer.joomla.org/security-centre/803-20200302-core-xss-in-protostar-and-beez3.html</a></li>
<li>Updated vm system pugin vmLoaderPluginUpdate. When a joomla user is deleted, the corresponding virtuemart data is now also deleted.</li>
<li>Deleting a Virtuemart user removes also the joomla user now</li>
</ul>
<h3>Payment Plugins</h3>
<ul>
<li>Big Update for Skrill by the Skrill Team Esphere, images compressed by iStraxx</li>
<li>Avatax, Fixes and adjustments by AXIOM</li>
<li>Realex Plugin removed trigger in createPmtRefTable, the trigger is already executed in $userFieldsModel-&gt;store($data);</li>
<li>Enhanced PayPal Smartbuttons. Shows PayPal Button now also when logged in.</li>
<li>Minors for Sofort</li>
<li>Minors for Paypal</li>
<li>Fixed core restrictions for skrill</li>
</ul>";}i:1;O:8:"stdClass":3:{s:4:"link";s:53:"https://virtuemart.net/news/498-bugfix-release-3-6-10";s:5:"title";s:88:"Bugfix Release 3.6.10 Important fix for category restriction of payment/shipment plugins";s:11:"description";s:7201:"<p style="text-align: justify;"><img src="https://virtuemart.net/images/virtuemart/news/VirtueMart-Doors.jpg" alt="VirtueMart Doors" />When a release is just around the corner, we stop adding new features and focus on testing and fixing bugs. So it's a normal reaction of our members to push their wishes after the release. So this new core has an above-average number of new features for a subversion. There are mainly two different groups of features. Some are like furniture. It is quite simple to add them and it is very unlikely that they break something. It may happen that the new table stands in the way of the rarely used door to the basement. But it is simple to fix. Most of the time our testers catch these issues, but sometimes the central heating door was not tested. The new features are all of that kind. The worst which can happen is, that they do not work.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: justify;">Bigger, more complex feature changes are done in the major versions such as VirtueMart 3.6. These feature changes are more similar to changing the room layout of a house, or adding an extra floor, or replacing the roof, and so on. These are changes which require much more testing and these are more likely to break other constructions attached to your house. For example, the beautiful balcony (your one-page checkout).</p>
<p style="text-align: justify;">Sometimes we notice that our house is not really comfortable. Displaying the order details below the order list was a good idea, but if the order list was too long then buyers did not see the order details. The order details open now above the list. This way, the logic makes much more sense. If you scroll down in the order details, you can directly select the next order. These and similar changes can be found below in the list of changed behaviors.</p>
<p style="text-align: justify;">One of them even starts with "fixed an issue editing the order...". This one is a very typical problem with the GUI, the graphical user interface. The difference of design and art. It sounds simple, but it is not easy to create a functional GUI. It is quite simple to create a nice looking GUI, but that only counts at first glance. In the long run, a GUI must work functionally. The whole order editing started as plain edit function without any assistance. When you changed a data, the new data was taken. A rough calculation system was added to help with the simple summation.<br /> <br />VirtueMart 3.6 extended the order editing assisting system. The tax change works by drop-down, but this system was not written for discounts. The problem here was to find an elegant GUI. For example, a VAT does not need to be overwritten. If you select a VAT, you expect a fail-proof calculation. But sometimes a discount is granted according to a certain rule or only as a result of a specific trade. As a result, changing a quantity of a discounted item did not change the discount according to the new quantity. The system accepted the discount the old way, as direct input.<br /> <br />The new system now works so, that the discount is always calculated by the given prices and multiplied by the quantity. However, if some of the required prices do not exist (for example, the undiscounted gross price), the discount value is transferred as direct input for the whole position. So you can still overwrite the discount by simply emptying the gross price. The GUI concepts follows the intuitive idea, that an empty price field is calculated by the existing data. So you can of course also just set the gross price and the discount and you will get your net and final price calculated automatically.</p>
<p style="text-align: justify;">Last but not least the bug-fixes. Sometimes it happens that a "furniture feature" turns out to be a roof changer ;-). The feature "automatic thumbnailing of the 'no image set' image" is one of these types. It led to a cascade of changes in the mediahandler.php file. The feature "remote images" remained silent in its corner and only caused problems there. But the simple sounding "automatic thumbnailing of the 'no image set' image" even caused problems when adding a new media because it suddenly behaved as thumbnail "no image set" image.<br />Sometimes a bugfix aggravates the problem. In German we use the word "verschlimmbessern", from "schlimm" (sad) and "bessern" (to make better). Test users reported that sometimes payment/shipment methods are not correctly selected in the cart, or not visible for selection. The provided fix solved the problem if categories were not set, but created another one. Adding the extra tests for the case 'on empty' lead to a wrongly used pattern and broke the category conditions.</p>
<p style="text-align: justify;">I hope that I gave a good insight how complex it is to deal with new features, bugs, features removing bugs, and bugfixes adding bugs, and so on.</p>
<p style="text-align: justify;">Thanks to our good community - join us at forum.virtuemart.net</p>
</div>
<h3>List of new features</h3>
<ul>
<li>Added placeholders to userfields</li>
<li>Added cloning of products with children</li>
<li>Added hidden config adminProductListBruttoPrices</li>
<li>Added option to user list in backend "show only shoppers"</li>
<li>Added vendor drop-down to users list in backend, so that it filters "shoppers of a vendor"</li>
<li>Added option to user account view "showUserShopperGrp"</li>
</ul>
<h3>Changed behaviour</h3>
<ul>
<li>Remote medias can now also be stored with http/s (is removed automatically)</li>
<li>Discontinued products are now only filtered for shoppers (not as managers in FE or BE)</li>
<li>Fixed an issue editing the order. Increasing the quantity of an item did not increase the given discount, but used the entered one. More information here&nbsp;<a class="external" href="http://forum.virtuemart.net/index.php?topic=143888.0" target="_blank" rel="noopener">http://forum.virtuemart.net/index.php?topic=143888.0</a></li>
<li>Order details are now opened above the order list</li>
<li>PayPal does not directly try to validate the data (for certain sub-methods), only when in checkout process</li>
<li>When ChangeShopperDeleteCart option is activated, then it also empties of the addresses of the current cart</li>
<li>If automatic payment/shipment is set to "none", the triggers are not executed</li>
</ul>
<h3>Enhancements</h3>
<ul>
<li>Component aio should work more robust now (some plugins prevented that it loaded the vmconfig correctly)</li>
<li>Added database key for product sku</li>
<li>Added delay of 400 ms to mediahandler autosearch function</li>
</ul>
<h3>List of fixes</h3>
<ul>
<li>Fix for no image display in media edit</li>
<li>Fixed category conditions for methods (shipment/payment)</li>
<li>Removed a note in router due a vmdebug</li>
<li>Fixed logic of storing username, when it is not allowed to change the username</li>
<li>Fix for adding new ST address in account maintenance view</li>
<li>Fixed typo in handle404 function</li>
</ul>";}i:2;O:8:"stdClass":3:{s:4:"link";s:76:"https://virtuemart.net/news/497-bugfix-release-3-6-8-registration-and-paypal";s:5:"title";s:53:"Bugfix Release 3.6.8 - Registration and PayPal issues";s:11:"description";s:2419:"<p style="text-align: justify;">An unexpected error occurred when updating to VirtueMart 3.6.4 using the All-in-One installer. The error was due to the uninitialized language object. First, we discovered that the VirtueMart files were loaded incorrectly when installing with third-party plug-ins present. But then all of a sudden, even with a completely new installation. The previous installation routine only checked whether the VmConfig class existed and executed the load configuration. The new installation routine also checks the existence of the vmLanguage class.<br /><br />And suddenly we had a problem with the PayPal IPN. If you google for the problem it is easy to see that it has been a periodically recurring problem. Our IPN function used the DNS records of the domains listed in the white list to check the IP. The new method uses a mixed mode and also checks if the requesting IP is resolved to the domains in the white list.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: justify;">Update for 3.6.6: Some third-party developers only include our class VmConfig, but do not execute the loadConfig function. In other places, we check whether the VmConfig class already exists, and include <em>AND executed loadConfig</em> only if the class has not already been loaded. The router and the system plugin for updates now specifically check whether loadConfig has actually been executed.</p>
</div>
<h3>Changed behaviour</h3>
<ul>
<li>3.6.6 Invoice download icon is now a button with the invoice number</li>
<li>3.6.6 Backend order list search now considers order id and order total now (round by 2 digits)</li>
</ul>
<h3>List of fixes</h3>
<ul>
<li>3.6.6.2 Fix for AIO installer, ensuring a correctly initialised vm config</li>
<li>3.6.8 Fix for user registration in account view</li>
<li>3.6.8 fix for not loaded joomla language if user activation is used (double opt-in)</li>
<li>3.6.8 Virtuemart registration email now uses the joomla parameter "sendpassword" correctly</li>
<li>3.6.8 checkPaypalIps now works with a mixed mode. Thanks to Studio42 for this idea. <a href="http://forum.virtuemart.net/index.php?topic=131735.msg508782#msg508782">http://forum.virtuemart.net/index.php?topic=131735.msg508782#msg508782</a></li>
</ul>";}i:3;O:8:"stdClass":3:{s:4:"link";s:88:"https://virtuemart.net/news/496-bugfix-release-3-6-4-outdated-payment-plugins-work-again";s:5:"title";s:67:"Bugfix Release 3.6.4 - 3.6.6 Outdated payment plugins do work again";s:11:"description";s:4365:"<p>With all the improvements that were included in the release of VirtueMart 3.6.0 in late August 2019, we had also introduced new restriction parameters for payment plugins provided by the core. However, the new core introduced a small incompatibility for older payment plugins and some VirtueMart shop owners were unable to update their payment plugins to work with the new core. But the lively VirtueMart community found a way to get the old plugins working again. There was a lot of input in the development forum and many constructive talks. <strong>Great community work!</strong></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: justify;">Update for 3.6.6: Some 3rd party developer just include our class VmConfig, but do not execute the loadConfig function. At other places, we check if the VmConfig class already exists, and include AND executed loadConfig only when the class was not loaded already. The Router and the Systemplugin for updates check now specifically, if loadConfig was actually executed.</p>
</div>
<h3>List of new features</h3>
<ul>
<li>added shared_stock for child products. They can use now the stock of the parent</li>
<li>added feature "Disable inheriting of customfields to children"</li>
<li>Added an option for the menu item which allows shoppers to register themselves directly as vendors</li>
<li>added the possibility to manage shoppers per vendor for multivendor mode "byvendor"</li>
<li>Enabled routing of different languages within one call (vmLang must be set extra on the VmTable, because the table instance has its own temporarly&nbsp;vmLang var)</li>
<li>added automatic thumbnailing of the image which indicates that not image is set</li>
<li>3.6.6 joomla user activation features work for virtuemart again</li>
</ul>
<h3>Changed behaviour</h3>
<ul>
<li>edit order items, changing order status of one item does not automatically fire an order update any longer, use "edit ordered products" and store the order.</li>
<li>checkCaptcha ask a question to vendor uses now vm config ask_captcha</li>
<li>storing of user data stores now always the data to the cart.</li>
<li>Cart stores address after confirmOrder</li>
<li>browse view, it could happen that products did not show products, omitLoaded was not correctly set for the group "products"</li>
<li>browse view, changed order of loading of product groups to ensure that "featured products" are not already displayed among "products"</li>
<li>3.6.6 Invoice download icon is now a button with the invoicenumber</li>
<li>3.6.6 Backend order list, search considers order id and order total now (round by 2 digits)</li>
</ul>
<h3>List of fixes</h3>
<ul>
<li>changed cart so that it works&nbsp;correctly again with old payment/shipment plugins&nbsp;</li>
<li>rating replaced old preg_replace filter against FILTER_SANITIZE_STRING</li>
<li>Important fix for creditcard.php, new php versions threw notice</li>
<li>Updated js of the cart. There were double binds. The JS now binds only radios and checkboxes</li>
<li>PayPal fixed product price for overridden price</li>
<li>calculationHelper, fixed category restriction for rules per billcalculationHelper, fixed category restriction for rules per bill</li>
<li>edit order items should consider the coupon_code now</li>
<li>correct order created, modified date</li>
<li>fix for product browse when legacy mode is enabled</li>
<li>small fix for ids of calendars</li>
<li>models product fixed saveorder for storing ordering = 0</li>
<li>vmLoaderPluginUpdate updated language, xml</li>
<li>added fallback for reuseorders=1</li>
<li>changed getTip of config view.html.php it now uses the same fallbacks for rowShopFrontSet and writePriceConfigLine</li>
<li>getCurrentUrlBy, added mode for returning query as array (not as URI, string)</li>
<li>added parameter task to function call "manage" in function isSuperVendor</li>
<li>mordel orderstatus, function getOrderStatusNames set to static and returns more data</li>
<li>model orders replaced direct sql against static getOrderStatusNames</li>
<li>vmpsplugin.php, restrictions added empty checks for !is_array</li>
<li>3.6.6 (fix for 3.6.4 thumbnail of no image set image was rendered to root folder)</li>
</ul>";}i:4;O:8:"stdClass":3:{s:4:"link";s:56:"https://virtuemart.net/news/494-bugfix-release-for-3-6-0";s:5:"title";s:24:"Bugfix release for 3.6.0";s:11:"description";s:4753:"<p>Implemented new restriction parameters provided by the VirtueMart core to our native payment plugins PayPal, Amazon Pay, Sofort, Authorize.net, eWay, heidelPay, Klarna, Skrill, 2checkout and Realex The latter also received a general update and has been renamed to 'globalpayments' because it&nbsp; was acquired by Global Payments Inc. some time ago.. There is a slight change in the handling of pending orders. The new procedure is described here: <a href="https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html">https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html</a></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;">&nbsp;</p>
</div>
<h3>New Features</h3>
<ul>
<li>Added disabling of inherited related products and related categories</li>
<li>Customfields for shoppergroups</li>
<li>External media: Create thumbnails on the fly directly from remote server. Added extra permission for uploading remote media</li>
</ul>
<h3>enhanced or changed behaviour</h3>
<ul>
<li>Removed automatically selected ‘replace’ when selecting a media for upload</li>
<li>Removed keeping of customfield search filters when switching categories</li>
<li>Reconsidered the function deleteOldPendingOrder. The sql now always considers the time. New behaviour described here:<br /> <a href="https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html">https://docs.virtuemart.net/manual/general-concepts/215-checkout-process.html</a></li>
<li>Added message of missing/not writeable folder to the checkPath function</li>
<li>The customer_notified function now works only for the emails of the customer, the vendor email is always sent according to the orderstatus</li>
</ul>
<h3>Bugs</h3>
<ul>
<li>Fixed missing array key in getPayment</li>
<li>Fixed missing renderShipmentDropdown in shipment view</li>
<li>Taxes per bill were accidently not added to the shipment tax calculation</li>
<li>fixed overwrite prices in Paypal Express. Invalid token set the cart paymentmethod always to 0, even when paypal was not selected</li>
<li>Fixed creation of extra plugin tables of plugins textinput and specification</li>
<li>The vmplugin onStoreInstallPluginTable had replaced a $name against $this-&gt;name</li>
<li>Fixed breadcrumb for menu item pointing to productdetails. When menu item name and productname is the same, the productname is not written twice.</li>
<li>Added missing getDbo in state model (thx GJC)</li>
<li>Invoice view: Fixed foreach loop for the shipment address</li>
<li>Fixed a new (old) bug in order editing for the case discount before VAT</li>
</ul>
<h3>Completed</h3>
<ul>
<li>Added missing language</li>
<li>Updated vmprices.js so that it works also for quantity buttons in the cart (thank you Abhhishek)</li>
<li>Added country Montenegro</li>
<li>Safepath config model, added JPath clean before storing of the Path, added more check cases for wrong paths</li>
<li>Prices replaced init and step against data-init and data-step (the JS has a fallback)</li>
<li>Customer_notified works now only for the emails of the customer, the vendor email is always sent according to the orderstatus</li>
</ul>
<h3>Of Interest for developers</h3>
<ul>
<li>Important fix in cart helper function checkAutomaticSelectedPlug, the automaticSelected.type variable is now only set to true, if there is only one method.</li>
<li>Plugins using the core restriction remove automatically the xml vars with the same name. So we can easily write backward compatible payment/shipment plugins. Please read here&nbsp;<a href="http://docs.virtuemart.net/tutorials/development/240-important-adjustments-for-virtuemart-3-6.html">http://docs.virtuemart.net/tutorials/development/240-important-adjustments-for-virtuemart-3-6.html</a></li>
<li>In the vmdefines function defines, changed default from <em>site</em> to <em>0</em>, if 0 is used the appId is taken from joomla</li>
<li>Added resetting of categoryRecursed in router and category model before calling getCategoryRecurse removed unsed code</li>
<li>For the weight_countries shipment plugin, address type just by STsameAsBT only</li>
<li>For function getVendorCurrency added a fallback for empty vendorId and a vmTrace to find the problem <a href="http://forum.virtuemart.net/index.php?topic=141856.msg506893#msg506893">http://forum.virtuemart.net/index.php?topic=141856.msg506893#msg506893</a></li>
<li>Added function getSafePathFor, which gives and if applicable creates a path for a certain topic. Old function checkSafePath now creates automatically the invoice path</li>
</ul>";}}s:6:"output";s:0:"";}