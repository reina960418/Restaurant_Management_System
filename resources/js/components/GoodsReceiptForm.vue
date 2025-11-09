<template>
    <form @submit.prevent="submitForm">
        <div class="row">
            <!-- Purchase Order Selection -->
            <div class="col-md-6 mb-3">
                <label for="purchase_order_id" class="form-label">採購單</label>
                <select class="form-control" id="purchase_order_id" v-model="form.purchase_order_id" @change="loadPurchaseOrderItems" required :disabled="isEditing">
                    <option value="" disabled>請選擇一張採購單</option>
                    <option v-for="po in purchaseOrders" :key="po.id" :value="po.id">
                        PO#{{ po.id }} - {{ po.supplier.name }} ({{ po.order_date }})
                    </option>
                </select>
            </div>
            <!-- Receipt Date -->
            <div class="col-md-6 mb-3">
                <label for="receipt_date" class="form-label">收貨日期</label>
                <input type="date" class="form-control" id="receipt_date" v-model="form.receipt_date" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">備註</label>
            <textarea class="form-control" id="notes" v-model="form.notes" rows="3"></textarea>
        </div>

        <hr>

        <h3>收貨品項</h3>
        <div v-if="form.items.length === 0 && form.purchase_order_id">
            <p>載入中或沒有品項。</p>
        </div>
        <div v-else-if="!form.purchase_order_id">
            <p>請先選擇一張採購單。</p>
        </div>
        <div v-else>
            <div v-for="(item, index) in form.items" :key="index" class="row align-items-end mb-2">
                <!-- Ingredient Name -->
                <div class="col-md-4">
                    <label class="form-label">食材</label>
                    <input type="text" class="form-control" :value="item.ingredient_name" readonly>
                </div>
                <!-- Ordered Quantity -->
                <div class="col-md-2">
                    <label class="form-label">訂購數量</label>
                    <input type="text" class="form-control" :value="item.ordered_quantity" readonly>
                </div>
                <!-- Quantity Received -->
                <div class="col-md-3">
                    <label class="form-label">收到數量</label>
                    <input type="number" step="0.01" class="form-control" v-model.number="item.quantity_received" :max="item.ordered_quantity" min="0" required>
                </div>
                <!-- Quantity Returned -->
                <div class="col-md-3">
                    <label class="form-label">退貨數量</label>
                    <input type="number" step="0.01" class="form-control" v-model.number="item.quantity_returned" min="0">
                </div>
                <!-- Return Reason -->
                <div class="col-md-12 mt-1" v-if="item.quantity_returned > 0">
                    <label class="form-label">退貨原因</label>
                    <input type="text" class="form-control" v-model="item.return_reason" required>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{ isEditing ? '更新進料單' : '儲存進料單' }}</button>
            <a :href="indexUrl" class="btn btn-secondary">取消</a>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        purchaseOrders: { type: Array, required: true },
        storeUrl: { type: String, default: '' },
        updateUrl: { type: String, default: '' },
        indexUrl: { type: String, required: true },
        initialData: { type: Object, default: () => null },
        isEditing: { type: Boolean, default: false },
    },
    data() {
        return {
            form: {
                purchase_order_id: '',
                receipt_date: new Date().toISOString().slice(0, 10),
                notes: '',
                items: []
            },
        };
    },
    created() {
        if (this.isEditing && this.initialData) {
            this.form.purchase_order_id = this.initialData.purchase_order_id;
            this.form.receipt_date = this.initialData.receipt_date;
            this.form.notes = this.initialData.notes;
            // Directly use initialData items, no need to call API
            this.form.items = this.initialData.items.map(item => ({
                ingredient_id: item.ingredient_id,
                ingredient_name: item.ingredient.name, // Assumes ingredient is loaded
                ordered_quantity: 'N/A', // In edit mode, we might not have original PO quantity easily
                quantity_received: item.quantity_received,
                quantity_returned: item.quantity_returned,
                return_reason: item.return_reason,
                price: item.price,
            }));
        }
    },
    methods: {
        loadPurchaseOrderItems() {
            // This method is now only for CREATE mode
            if (this.isEditing) return;

            const selectedPurchaseOrder = this.purchaseOrders.find(
                po => po.id === this.form.purchase_order_id
            );

            if (selectedPurchaseOrder) {
                axios.get(`/api/purchase-orders/${selectedPurchaseOrder.id}/items`)
                    .then(response => {
                        this.form.items = response.data.map(item => ({
                            ingredient_id: item.ingredient_id,
                            ingredient_name: item.ingredient.name,
                            ordered_quantity: item.quantity,
                            quantity_received: item.quantity,
                            quantity_returned: 0,
                            return_reason: '',
                            price: item.price,
                        }));
                    })
                    .catch(error => {
                        console.error("Error loading purchase order items:", error);
                        this.form.items = [];
                        alert('無法載入採購單品項。');
                    });
            } else {
                this.form.items = [];
            }
        },
        submitForm() {
            const url = this.isEditing ? this.updateUrl : this.storeUrl;
            const method = this.isEditing ? 'put' : 'post';

            axios[method](url, this.form)
                .then(response => {
                    window.location.href = this.indexUrl;
                })
                .catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
                        let messages = Object.values(error.response.data.errors).flat().join('\n');
                        alert('儲存失敗：\n' + messages);
                    } else {
                        alert('發生未知錯誤');
                    }
                    console.error(error);
                });
        }
    }
};
</script>