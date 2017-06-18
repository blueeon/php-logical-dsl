/**
* 语法检查:格式,语法,字符
*
*/

rule1{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order') AND req.order.price >= 1000
    THEN
        (res.mihome = 100 AND res.express = 10 AND WEIGHT=30),
        (res.mihome = 112 AND res.express = 10 AND WEIGHT=70)
    PRIORITY = 1
}
rule2{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-tmall')
    THEN
        (res.mihome = 110 AND WEIGHT=10)
    PRIORITY = 2
}