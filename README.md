# php-logical-dsl
A simple DSL for WHEN and THEN

## Installation
## Try it!
## Features
## Syntax Definition
### Prototype
```
rule1[
    WHEN{ req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order') AND req.order.price >= 1000}
    
    THEN{
    (res.mihome = 100 AND res.price = 100 AND WEIGHT=30), 
    (res.mihome = 112 AND res.price = 100 AND WEIGHT=70)
    }
    
    除此之外{ PRIORITY = 1}
]
[RULE NAME]{
    WHEN:     [[Condition]...];
    THEN:     [[Result]...];
    PRIORITY: [priority];
}
...
```

### Example



## Usefull Tools

