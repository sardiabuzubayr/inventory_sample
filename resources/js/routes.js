// resources/js/routes.js
import Item from './components/item/Item.vue'
import Sales from './components/sales/Sales.vue'
import NewSales from './components/sales/NewSales.vue'
import Customer from './components/customer/Customer.vue'

export default {
    routes: [
        {
            path: '/sales',
            component: Sales,
            name: 'sales',
            meta: {
                title: 'Sales'
            },
        },
        {
            path: '/new_sales',
            component: NewSales,
            name: 'new_sales',
            meta: {
                title: 'New Sales'
            },
        },
        {
            path: '/customer',
            component: Customer,
            name: 'customer',
            meta: {
                title: 'Customer'
            },
        },
        {
            path: '/item',
            component: Item,
            name: 'item',
            meta: {
                title: 'Item',
            },
        },
    ],
};