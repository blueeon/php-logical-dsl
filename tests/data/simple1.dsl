/**
 * 一个带权重的简单例子,两个规则之间没有交集
 */


rule1     {
    when   req.order.order_type = 10
    and(
        req.order.stock_channel  IN ( 'cn-tmall')
        OR req.order.price >= 1000
        AND req.order.avg_price >= 1000/3
        AND req.order.order_from IN(1,2,3,12)
    )
    THEN
        (res.mihome = 100 * 2 AND res.price = 100 AND WEIGHT=30),
        (res.mihome = 112 AND res.price = 100 AND WEIGHT=70)
}
rule2{
    WHEN      req.order.order_from = 12 AND req.order.stock_channel IN( 'cn-order') AND req.order.price >= 1000
    THEN    res.mihome = 100 AND res.price = 100
    PRIORITY = 10
}

