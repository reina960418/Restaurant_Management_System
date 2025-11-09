/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

// Import components
import PurchaseOrderForm from './components/PurchaseOrderForm.vue';
import GoodsReceiptForm from './components/GoodsReceiptForm.vue';

// Mount PurchaseOrderForm if its container exists
const poFormContainer = document.getElementById('purchase-order-form');
if (poFormContainer) {
    const poApp = createApp({});
    poApp.component('purchase-order-form', PurchaseOrderForm);
    poApp.mount(poFormContainer);
}

// Mount GoodsReceiptForm if its container exists
const grFormContainer = document.getElementById('goods-receipt-form');
if (grFormContainer) {
    const grApp = createApp({});
    grApp.component('goods-receipt-form', GoodsReceiptForm);
    grApp.mount(grFormContainer);
}
