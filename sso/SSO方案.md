#SSO 方案#

## SSO 登录原理 ##

>  SSO (Single Sign ON)，中文名称为单点登录，用于帮助用户在多个相互信任的系统应用中只需登录一次便可使用所有的应用，省去用户多次登录的麻烦。

> 比如在这样一种场景中：现在有两个app,分别为app1和app2，在未使用SSO系统的情况下，用户要使用app1和app2时只能分别在app1和app2上进行登录。在有SSO系统的情况下，用户在第一次进行登录时，不论此时用户登录的是app1还是app2，都会跳转到SSO认证系统。SSO认证用户为无效用户时，用户登录失败。SSO认证用户为有效用户时，此时SSO认证系统便会发给用户一个唯一的认证凭证--ticket，这时候用户访问另一个app时，会将这个ticket也发送至SSO认证系统，SSO对这个ticket进行验证，验证成功后，用户便可直接使用这个app而不需要再进行一次登录。

>由上面的场景中可以看出SSO有如下的条件：

> 1. 所有app应用都需要访问SSO系统进行用户登录认证;
> 2. 所有app应用都能够识别和提取ticket信息;
> 3. 所有app应用都能够识别用户的登录状态;

>以上3点是SSO系统所要实现的基本内容。

## 备选方案 ##
根据应用的不同，SSO实现可以使用以下主要三种备选方案： 
> 1. jsonp 方式
> 2. iframe + js 方式
> 3. 页面直接跳转方式




## SSO 方案原理 ##

> SSO的实现是基于cookie的。SSO系统发送给客户端的ticket本质上是要写到客户端cookie中。用户使用应用进行登录时，即要写应用所在域的cookie也要写SSO域的cookie。用户登录一个应用app后再使用另一个域的app时，还是要通过SSO进行判断是否已经登录，在已经登录过的情况下只需要添加这个app应用的域上的cookie到客户端浏览器。

> SSO主要的实现过程可以归结为以下步骤：
> 1. 所有的用户账号信息要全部提取到同一个域（SSO服务所在域）的数据库中（或使用文件方式），用户登录的验证及信息提取全部由这个SSO系统控制进行。
> 2. app应用可保留原来的登录页面但app应用本身不再进行登录验证，用户登录验证部分都会经app系统提交至SSO系统进行。
> 3. 若在SSO域内没有找到用户登录凭证（ticket），说明用户尚未登录。用户登录成功后，用户浏览器会写SSO域内的登录cookie，最后由app应用返回用户最初请求的页面，app所使用的用户信息也应全部由SSO系统返回。
> 4. 当用户已经登录某一app应用后再尝试使用另一个需要登录验证的app应用时，这个app首先会访问SSO系统查看用户是否已经登录并将查询结果返回给app应用。
> 5. 用户在已登录的情况下进行登出时，app首先访问SSO进行登出操作，SSO收回ticket凭证，消除用户登录状态。
> 6. 其它app在用户的每一次请求时都需要经SSO检查用户的登录状态，若SSO判定用户已登出时，app应立即置自身域内用户状态为登出状态，并跳转用户到登录页面。


## SSO 方案 ##

> 首先设定目前这样三个地址：

> 1. www.domain1.com
> 2. www.domain2.com
> 3. www.sso.com

> domain1 和 domain2 是应用地址，sso是单点登录验证中心。为了尽最大限度的减少对已有的应用登录进行修改，对于应用提供一个 serviceController.js 文件用于对登录的整个过程进行控制，应用仅需修改该 js 文件的一些配置参数即可。考虑到需要进行 js 的跨域操作，在该 js 文件中将采用 jsonp方式进行用户登录等跨域操作。以下对整个SSO系统的运行过程进行说明。

> 1. 用户访问 www.domain1.com/login.html ，这是该应用的登录页面，应用加载 serviceController.js 文件，该 js 依赖 jquery 和 jquery.cookie 插件。
> 2. serviceController.js 文件加载完成后首先检查目前该浏览器用户是否已经在 SSO 中心登录，查询地址为 www.sso.com/login.php。若用户已经登录，则SSO返回用户的登录信息，serviceController.js 根据返回结果做相应处理。
> 3. 上一步返回结果若显示用户尚未登录，则应用应该向SSO中心请求一个nonce作为一次登录凭证，请求地址为 www.sso.com/prelogin.php 。
> 4. sso.com/prelogin.php 会在每一次得到请求时生成一个由字母和数字组成的六位随机字符串。nonce 生成后会作为键存储于memcache中，对应的值为servertime（请求nonce时的SSO系统时间戳，由php time()函数产生）。最后返回生成的nonce和servertime到应用。
> 5. 用户进行登录，serviceController.js 会将用户的登录信息（uname，upsw)和上一步取得的nonce和servertime以及当前应用名称（service = domain1）全都提交给 www.sso.com/login.php 进行登录处理。用户密码不能明文发送，需要先将upsw使用SHA1连续加密两次，再将加密后的upsw和servertime，nonce 进行拼接后再使用SHA1加密一次，最后的加密结果作为最终的用户密码进行发送。
> 6. SSO接收到 serviceController.js 发送过来的用户登录信息后，验证用户登录信息，并将验证结果返回给 serviceController.js。serviceController.js 根据结果进行失败提示或跳转等操作。
> 7. 若用户在domain1登录后又访问 www.domain2.com ，因为domain1 和domain2 都是 SSO中注册的应用（service），当domain2进行用户是否已经登录的检查（步骤2）时发现用户之前已经登录了，则此时 domain2 的serviceController.js 会做相应的登录后处理（一般是跳转到指定页面）。 
> 8. 用户在已经登录的前提下可以进行登出操作。点击登出按钮后，serviceController.js 访问 SSO，请求进行登出。SSO 根据 cookie ticket（session id) 删除该用户的登录 session并返回登出结果。serviceController.js 根据返回结果进行操作。

## SSO 方案中需要考虑问题 ##

> 上面提到的SSO方案中存在一些需要进一步分析和处理的问题，如以下：

> * nonce 存储问题

> >每一个nonce只能使用一次，nonce可以用来保障用户的登录时一次性的，是不可重复的。用户登录的 password 也使用nonce进行了加密，所以应用端提供的用户登录密码也是变化的。所以nonce机制在一定程度上提高了系统安全性。在SSO上需要使用memcache来保存nonce，一旦nonce被使用便需要删除这个nonce。

> * session 数据存储问题
> > 如果系统访问的用户数量比较大，session 的存储方式会对系统效率产生比较大的影响。Session 存储可以使用文件或数据库方式。这两种方式session其实都是存储于硬盘上，文件存储方式不适合大数据量，数据库存储方式对数据量基本无限制，但对系统负载可能会产生比较大的压力。可以在数据库存储方式之上增加memcache进行缓存处理，将比较热的数据存储于memcache之中，这样可以减少多数据库的访问，提高系统响应速度。


> * session 过期处理方案
> > 目前session存储于memcached与数据库中，过期的session不能自动从memcached和数据库中进行清理。需要进行手动的清理。若用户的session过期后，memcached中的数据不需要进行处理，因为login_chk会检查用户session是否过期，对于过期的数据会由memcached自动的剔除掉（当memcached空间不够时）。

> * js 跨域问题
> >登录检查，登录和登出都需要进行跨域访问，serviceController.js 采用 jsonp 机制进行 js 跨域访问。由于 jsonp 使用的是 GET 方式，因而在用户比较敏感的数据上可能需要进行加密处理。


> * 通信安全问题

> 1. domain1 如何得知其拿到的登录确认是从 sso 发送过来的？（是否需要添加一步对返回结果进行认证的处理过程）

> 使用 RSA 非对称密钥加密机制实现 SSO和应用之间的通信信任问题。JS使用SSO的公钥进行加密，PHP使用SSO的私钥进行解密。JS不能使用公钥对PHP使用私钥加密的数据进行解密。
在PHP中使用开启openssl模块支持，使用openssl_pkey_new()函数产生新的RSA密钥对，使用openssl_pkey_export()取得私钥，使用openssl_peky_get_details()取得公钥信息，取得公钥的N和E并转为16进制格式，最后将公钥N和E发送给js客户端。客户端需要使用公钥N和E对发送信息进行加密。PHP服务端使用私钥进行解密。

> 在 httpd.conf 中 uncomment LoadModule ssl_module modules/mod_ssl.so 
在  php.ini 中 uncomment extension=php_openssl.dll，修改这俩个配置文件后，openssl方可开启。
> openssl_pkey_new() 返回 bool 而非 resource，

> 2. ticket 生成规则改变，目前使用sessionid的方式是否合适？
> 最初使用sessionid作为ticket。这种方式容易被识别，默认session id 长度为13个字符，攻击者可以使用暴力方式进行穷举查询。虽然可以限制同一ip和同一账户的单位时间尝试登陆次数，但是仍然可以进一步从ticket上加强安全。ticket 不要使用 session id，而要使用某种自定的加密方式，要确保 ticket的唯一性。现在的 sessionid 在cookie中的名称为 allyes_sso，凭借用户标示（email）检查是否登录时需要根据 uemail -> session_id -> session。

> 3. 单 ip 多次请求和多 ip 多次请求单用户数据的防范处理？
> 应该设置请求次数限制，对于过多的请求不予应答处理，而是通知其稍后再进行尝试。目前尚未完成此功能。

> 4. 现有系统的迁移
> 若现有的多个系统有各自的登录控制功能，若用户使用相同的邮箱在若干系统之上都进行过注册，SSO应该如何进行处理？




> 5. 演示ppt


> 6. api 的实现
用户登录状态查询api, 地址为：sso/api/getUserLoginStatus.php?uid=*。根据提交的uid值进行用户登录状态的查询。返回数据为json格式。

api 可否以RESTful方式实现？



## admin 管理 ##
> SSO需要有admin来对用户及应用进行管理。

> admin 登录
>> SSO本身是用来进行登录控制的，对于admin的登录是否需要使用SSO本身的登录管理控制机制是需要进行考虑的。原则上，对系统本身的功能进行利用是有益的，但是原本一般用户的登录只是用来进行多应用共享的，而admin本身只限于SSO本域内，不存在多应用共享，因而使用SSO本身的登录功能是否合适需要再次考虑。总归来讲，可以利用SSO本身的登录功能来让admin进行登录，但是原有的功能需要进一步扩展，首先是对admin身份的识别，然后需要对登录状态检查等进行重构。

## demo ip ##
> 10.200.33.33







