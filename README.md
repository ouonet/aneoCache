# aneo/cache
aneo/cache is a php cache library, which keeps content fresh,via updating content upon source is changed.


Although there are a lot of php cache libraries,  I still have not found(no time to try one by one) what I need. For example, doctrine/cache is very famouse one,it will keep data until expiration.if source is changed before expiration, it won't refresh it.Another inconvenience is :doctrine/cache always use php serialize/unserialize to save/load data, low performance.

# Requirment #
We want to save heavy-time-consuming result data for saving time to get this result next time,we need a cache library to do：

1. client can save result
1. client can get result 
1. cache should refresh content if source is changed.  
1. client can decide where to save,eg. disk ,memcache ,apc....
2. Serializing result is not cache's responsibility, it's client's.

# Cache strategy #

Strategy is quit simple,upon client require data:

1. if data is in memory(an array),return it
1. else if data is persisted and source is not modified since last time, load it to memory ,and return it
2. otherwise ask source provider to get data,persist it ,then return it.

# Feature#

1. refresh content upon source is changed
1. can save data to file, memcache.

# Usage #
##Install##
    composer require aneo/cache
##  Guide/Example  ##

**prepare CacheDataProvider**

To use cache,should provide a CacheDataProvider,to generate data and id,and determinate validation.

    use aneo\cache\CacheDataProvider;
    
    Class HtmlTemplate implements  CacheDataProvider{
        function get($name)
        {
           return $this->compile($name);
        }
        
        function isModifiedSince($name, $time)
        {
           return filemtime($name)>$time;
        }
        
        function cacheId($name)
        {
           return 'htmlTemplate\\'.$name.'.php';
        }
        
        /**
         * @param string $name
         * @return string
         */
        function compile($name){
            //.............
        }
    }
 
After prepared cacheDataProvider, we can use cache:

**save to file**
	
    $cache=new CacheByFile('path/to/cache');
    $dataProvider= new HtmlTemplate();//a class implements interface aneo/cache/CacheDataProvider
    $name= 'foo.html';
    $result=$cache->get($name,$dataProvider);

**save to memcache**

    $memcache_obj = new Memcache;
    $memcache_obj->connect('127.0.0.1', 11211);
    $cache = new CacheByMemcache($memcache_obj);
    $dataProvider= new HtmlTemplate();//a class implements interface aneo/cache/CacheDataProvider
    $name= 'foo.html';
    $result=$cache->get($name,$dataProvider);

**serialize/unserialze/initialize**
    
In mostly case , data is not string. to persist it , we need translate it to string. How to translate it to string ,it depends on dataProvider, eg.,we can use serialze,json_encode, var_export... . And after we reload it , we need translate it to correct object,eg. we can use unserialize, json_decode,eval... . Further on, we need re-initial it . So, dataProvider should implement three optional function :
     
1. function encode($data);
1. function decode($data);
2. function initial($data);

Cache will auto detect these functions , and call them. 
