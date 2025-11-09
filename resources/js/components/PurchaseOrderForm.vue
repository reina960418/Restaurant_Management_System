<template>
    <form @submit.prevent="submitForm">
        <div class="row">
            <!-- Supplier Selection -->
            <div class="col-md-6 mb-3">
                <label for="supplier_id" class="form-label">廠商</label>
                <select class="form-control" id="supplier_id" v-model="form.supplier_id" required>
                    <option value="" disabled>請選擇一個廠商</option>
                    <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                        {{ supplier.name }}
                    </option>
                </select>
            </div>
            <!-- Order Date -->
            <div class="col-md-6 mb-3">
                <label for="order_date" class="form-label">訂單日期</label>
                <input type="date" class="form-control" id="order_date" v-model="form.order_date" required>
            </div>
        </div>

        <hr>

        <h3>採購品項</h3>
        <div v-for="(item, index) in form.items" :key="index" class="row align-items-end mb-2">
            <!-- Ingredient -->
            <div class="col-md-5">
                <label class="form-label">食材</label>
                <select class="form-control" v-model="item.ingredient_id" @change="updatePrice(index)" required>
                    <option value="" disabled>選擇食材</option>
                    <option v-for="ingredient in ingredients" :key="ingredient.id" :value="ingredient.id">
                        {{ ingredient.name }} ({{ ingredient.unit }})
                    </option>
                </select>
            </div>
            <!-- Quantity -->
            <div class="col-md-3">
                <label class="form-label">數量</label>
                <input type="number" step="0.01" class="form-control" v-model.number="item.quantity" placeholder="數量" min="0.01" required>
            </div>
            <!-- Price -->
            <div class="col-md-3">
                <label class="form-label">單價</label>
                <input type="number" step="0.01" class="form-control" v-model.number="item.price" placeholder="單價" min="0" required>
            </div>
            <!-- Remove Button -->
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" @click="removeItem(index)">X</button>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mt-2" @click="addItem">新增品項</button>

        <hr>

        <div class="d-flex justify-content-end">
            <h4>總金額: {{ totalAmount.toFixed(2) }}</h4>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{ isEditing ? '更新採購單' : '儲存採購單' }}</button>
            <a :href="indexUrl" class="btn btn-secondary">取消</a>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        suppliers: { type: Array, required: true },
        ingredients: { type: Array, required: true },
        storeUrl: { type: String, default: '' },
        updateUrl: { type: String, default: '' },
        indexUrl: { type: String, required: true },
        initialData: { type: Object, default: () => null },
        isEditing: { type: Boolean, default: false },
    },
    data() {
        return {
            form: {
                supplier_id: '',
                order_date: new Date().toISOString().slice(0, 10),
                items: [
                    {
                        ingredient_id: '',
                        quantity: 1,
                        price: 0,
                    }
                ]
            }
        };
    },
    computed: {
        totalAmount() {
            return this.form.items.reduce((total, item) => {
                return total + (item.quantity * item.price);
            }, 0);
        }
    },
    created() {
        if (this.isEditing && this.initialData) {
            this.form.supplier_id = this.initialData.supplier_id;
            this.form.order_date = this.initialData.order_date;
            this.form.items = this.initialData.items.map(item => ({
                ingredient_id: item.ingredient_id,
                quantity: item.quantity,
                price: item.price,
            }));
        }
    },
    methods: {
        updatePrice(index) {
            const selectedIngredient = this.ingredients.find(
                ingredient => ingredient.id === this.form.items[index].ingredient_id
            );
            if (selectedIngredient) {
                this.form.items[index].price = selectedIngredient.price || 0;
            }
        },
        addItem() {
            this.form.items.push({
                ingredient_id: '',
                quantity: 1,
                price: 0,
            });
        },
        removeItem(index) {
            this.form.items.splice(index, 1);
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
