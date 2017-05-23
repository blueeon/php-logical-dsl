/**
* 逻辑覆盖问题检查,不带权重参数时,检查逻辑条件是否存在重合,或者没有完全覆盖的问题
*/
rule1{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order') AND req.order.price >= 1000
    THEN
        (res.mihome = 100 AND res.express = 10 AND WEIGHT=30),
        (res.mihome = 112 AND res.express = 10 AND WEIGHT=70)
}
rule2{
    WHEN  req.order.order_from = 11 AND req.order.stock_channel NOT IN( 'cn-order')
    THEN
        (res.mihome = 110 AND WEIGHT=10),
        (res.mihome = 114 AND WEIGHT=90)
}