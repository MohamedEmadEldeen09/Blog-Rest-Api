<!-- <template>
    <div>
        <h1>Create New Blog</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label for="title">Title:</label>
                <input type="text" v-model="title" id="title" required />
            </div>
            <div>
                <label for="content">Content:</label>
                <textarea v-model="content" id="content" required></textarea>
            </div>
            <div>
                <label for="images">Images:</label>
                <input type="file" @change="handleFileUpload" multiple />
            </div>
            <button type="submit">Create Blog</button>
        </form>

        <hr />

        <button @click="deleteBlogImage">Delete Blog Image</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const title = ref('');
const content = ref('');
const images = ref([]);

const handleFileUpload = (event) => {
    images.value = Array.from(event.target.files);
};

const submitForm = async () => {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('content', content.value);
    formData.append('catagory_id', 3);
    images.value.forEach((image, index) => {
        formData.append(`images[${index}]`, image);
    });

    try {
        const response = await axios.post('http://localhost:8000/api/channel/421/blog', formData, {
            headers: {
                'Authorization': 'Bearer 11|hiaf6gw5GvGF0qjorCKHOMG22Z5LQfYHM9TGQaq13fc7421b',
            },
        });
        console.log('Blog created successfully:', response.data);
    } catch (error) {
        console.error('Error creating blog:', error);
    }
};

const deleteBlogImage =  async () => {
    if (confirm("Are you sure you want to delete this image?")) {
        try {
        const response = await axios.delete("http://localhost:8000/api/channel/421/blog/37553/image/45646", {
            headers: {
                'Authorization': 'Bearer 11|hiaf6gw5GvGF0qjorCKHOMG22Z5LQfYHM9TGQaq13fc7421b'
            }
        });

        console.log(response.status);
        console.log("Success:", response.data);
        } catch (error) {
            console.error("Error:", error);
        }
    }
    
    return;
}
</script>
 -->
