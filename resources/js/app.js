/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

import PurchaseOrderForm from './components/PurchaseOrderForm.vue';
import GoodsReceiptForm from './components/GoodsReceiptForm.vue';
import DishForm from './components/DishForm.vue';
import OrderForm from './components/OrderForm.vue';

const app = createApp({});

app.component('purchase-order-form', PurchaseOrderForm);
app.component('goods-receipt-form', GoodsReceiptForm);
app.component('dish-form', DishForm);
app.component('order-form', OrderForm);

app.mount('#app');

// Create separate Vue instances for specific forms
document.querySelectorAll('[id^="purchase-order-form-"]').forEach(el => {
    createApp(PurchaseOrderForm).mount(el);
});

document.querySelectorAll('[id^="goods-receipt-form-"]').forEach(el => {
    createApp(GoodsReceiptForm).mount(el);
});

document.querySelectorAll('[id^="dish-form-"]').forEach(el => {
    createApp(DishForm).mount(el);
});

document.querySelectorAll('[id^="order-form-"]').forEach(el => {
    createApp(OrderForm).mount(el);
});
