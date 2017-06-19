# php-logical-dsl

A simple DSL for WHEN and THEN

## Installation

```
composer require blueeon/php-logical-dsl
```
## Packgist Page

https://packagist.org/packages/blueeon/php-logical-dsl

## Try it!

## Features
## Syntax Definition
### Prototype

```
/**
 *
 *Test rules
 */
rule_name{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order') AND req.order.price >= 1000
    THEN
        (res.mihome = 100 AND res.price = 100 AND WEIGHT=30),
        (res.mihome = 112 AND res.price = 100 AND WEIGHT=70)
    PRIORITY = 1
}
rule_name{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order') AND req.order.price >= 1000
    THEN
        (res.mihome = 100 AND res.price = 100 AND WEIGHT=30),
        (res.mihome = 112 AND res.price = 100 AND WEIGHT=70)
    PRIORITY = 2
}
[RULE NAME]{
    WHEN        [[Condition]...]
    THEN        [[Result]...]
    PRIORITY =  [priority]
}
...

```

### Example

#### 1. simple
```
/**
 * 一个简单例子
 */


rule1{
    when    req.order.order_type = 10
    THEN    res.price = 110
}
rule2{
    WHEN    req.order.order_type = 11
    THEN    res.price = 500
}
```
#### 2. With PRIORITY
```
/**
 * 一个带权重的简单例子
 */
rule1{
    when    req.order.order_type = 10
    THEN    res.price = 110
    PRIORITY = 1
}
rule2{
    WHEN    req.order.order_type = 10 AND req.order.order_from = 12
    THEN    res.price = 500
    PRIORITY = 100
}
```
#### 3. Multiple result
```
/**
 * 随机按比例返回结果
 */
rule1{
    when    req.order.order_type = 10
    THEN    (res.express = 110 AND WEIGHT = 10),
            (res.express = 120 AND WEIGHT = 90)
}
rule2{
    WHEN    req.order.order_type = 11
    THEN    (res.express = 114 AND WEIGHT = 10),
            (res.express = 118 AND WEIGHT = 90)
}
```
#### 4. Complex example
```
/**
 * 带权重,多返回值,复杂判断逻辑
 */


rule1{
    WHEN    req.order.order_type = 10
    AND(
            req.order.stock_channel  NOT IN ('cn-tmall')
            OR  req.order.price >= 1000
            AND req.order.order_from IN(1,2,3,12)
    )
    THEN
        (res.mihome = 100 AND res.price = 110 AND WEIGHT=30),
        (res.express = 14  AND res.mihome = 112 AND res.price = 1200 AND WEIGHT=70)
    PRIORITY = 1
}
rule2{
    WHEN    req.order.order_from = 12 
            AND req.order.stock_channel IN( 'cn-order') 
            AND req.order.price >= 1000
    THEN    res.mihome = 100 AND res.price = 1000
    PRIORITY = 10
}


```



## Usefull Tools

