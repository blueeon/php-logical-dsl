/**
 * 一个带权重的简单例子,两个规则之间没有交集
 */


rule1     {
    when   req.order.order_type = 10
    and(
        req.order.stock_channel  NOT IN ( 'cn-tmall')
        OR req.order.price >= 1000
        AND req.order.order_from IN(1,2,3,12)
    )
    THEN
        (res.mihome = 100 AND res.price = 110 AND WEIGHT=30),
        (res.express = 14  AND res.mihome = 112 AND res.price = 1200 AND WEIGHT=70)
    PRIORITY = 1
}
rule2{
    WHEN      req.order.order_from = 12 AND req.order.stock_channel IN( 'cn-order') AND req.order.price >= 1000
    THEN    res.mihome = 100 AND res.price = 1000
    PRIORITY = 10
}

