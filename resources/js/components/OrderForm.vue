<template>
    <form @submit.prevent="submitOrder">
        <div class="mb-3">
            <label for="table_number" class="form-label">桌號 (可選)</label>
            <input type="text" class="form-control" id="table_number" v-model="form.table_number">
        </div>

        <hr>

        <h3>訂單品項</h3>
        <div v-for="(item, index) in form.items" :key="index" class="row align-items-center mb-2">
            <div class="col-md-6">
                <label class="form-label">菜色</label>
                <select class="form-control" v-model="item.dish_id" @change="updateDishPrice(index)" required>
                    <option value="" disabled>選擇菜色</option>
                    <option v-for="dish in dishes" :key="dish.id" :value="dish.id">
                        {{ dish.name }} (NT$ {{ dish.price }})
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">數量</label>
                <input type="number" class="form-control" v-model.number="item.quantity" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">單價</label>
                <input type="text" class="form-control" :value="item.price_at_order" readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger w-100" @click="removeItem(index)">移除</button>
            </div>
        </div>
        <button type="button" class="btn btn-success mt-2" @click="addItem">新增菜色</button>

        <hr>

        <h4 class="text-end">總金額: NT$ {{ totalAmount.toFixed(2) }}</h4>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">送出訂單</button>
            <a :href="indexUrl" class="btn btn-secondary">取消</a>
        </div>
    </form>
</template>

<script>
export default {
    props: {
        dishes: { type: Array, required: true },
        storeUrl: { type: String, required: true },
        indexUrl: { type: String, required: true },
    },
    data() {
        return {
            form: {
                table_number: '',
                items: [],
            },
        };
    },
    computed: {
        totalAmount() {
            return this.form.items.reduce((sum, item) => {
                return sum + (item.quantity * item.price_at_order);
            }, 0);
        }
    },
    created() {
        this.addItem(); // Add one item by default
    },
    methods: {
        addItem() {
            this.form.items.push({
                dish_id: '',
                quantity: 1,
                price_at_order: 0,
            });
        },
        removeItem(index) {
            this.form.items.splice(index, 1);
        },
        updateDishPrice(index) {
            const selectedDish = this.dishes.find(
                dish => dish.id === this.form.items[index].dish_id
            );
            if (selectedDish) {
                this.form.items[index].price_at_order = selectedDish.price;
            }
        },
        submitOrder() {
            // Calculate total amount before submitting
            this.form.total_amount = this.totalAmount;

            axios.post(this.storeUrl, this.form)
                .then(response => {
                    alert('訂單已成功送出！');
                    window.location.href = this.indexUrl;
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        // Handle validation errors
                        if (error.response.data.errors) {
                            let messages = Object.values(error.response.data.errors).flat().join('\n');
                            alert('送出訂單失敗：\n' + messages);
                        } 
                        // Handle custom error messages (like stock issue)
                        else if (error.response.data.message) {
                            alert('錯誤：\n' + error.response.data.message);
                        }
                        else {
                            alert('發生未知錯誤');
                        }
                    } else {
                        alert('發生網路或未知錯誤');
                    }
                    console.error(error);
                });
        }
    }
};
</script>