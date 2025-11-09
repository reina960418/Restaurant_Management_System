<template>
    <form @submit.prevent="submitForm">
        <div class="mb-3">
            <label for="name" class="form-label">菜色名稱</label>
            <input type="text" class="form-control" id="name" v-model="form.name" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">價格</label>
            <input type="number" step="0.01" class="form-control" id="price" v-model.number="form.price" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">描述</label>
            <textarea class="form-control" id="description" v-model="form.description" rows="3"></textarea>
        </div>

        <hr>

        <h3>所需食材</h3>
        <div v-for="(item, index) in form.ingredients" :key="index" class="row align-items-end mb-2">
            <div class="col-md-6">
                <label class="form-label">食材</label>
                <select class="form-control" v-model="item.ingredient_id" required>
                    <option value="" disabled>選擇食材</option>
                    <option v-for="ingredient in ingredients" :key="ingredient.id" :value="ingredient.id">
                        {{ ingredient.name }} ({{ ingredient.unit }})
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">用量</label>
                <input type="number" step="0.01" class="form-control" v-model.number="item.quantity" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" @click="removeItem(index)">移除</button>
            </div>
        </div>
        <button type="button" class="btn btn-success mt-2" @click="addItem">新增食材</button>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">{{ isEditing ? '更新菜色' : '儲存菜色' }}</button>
            <a :href="indexUrl" class="btn btn-secondary">取消</a>
        </div>
    </form>
</template>

<script>
export default {
    props: {
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
                name: '',
                price: 0,
                description: '',
                ingredients: []
            },
        };
    },
    created() {
        if (this.isEditing && this.initialData) {
            this.form.name = this.initialData.name;
            this.form.price = this.initialData.price;
            this.form.description = this.initialData.description;
            this.form.ingredients = this.initialData.ingredients.map(item => ({
                ingredient_id: item.id,
                quantity: item.pivot.quantity,
            }));
        } else {
            this.addItem(); // Add one item by default for new dish
        }
    },
    methods: {
        addItem() {
            this.form.ingredients.push({
                ingredient_id: '',
                quantity: 1,
            });
        },
        removeItem(index) {
            this.form.ingredients.splice(index, 1);
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