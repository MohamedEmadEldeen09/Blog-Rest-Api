<!-- <template>
    <div>
        <h1>Image Upload</h1>
        <input type="file" @change="onFileChange" />
        <button @click="uploadImage">Upload</button>
        <button @click="deleteImage">Delete</button>
        <button @click="changeImage">Update</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const selectedFile = ref(null);

const onFileChange = (event) => {
    selectedFile.value = event.target.files[0];
};

const uploadImage = async () => {
    if (!selectedFile.value) {
        alert("Please select a file first!");
        return;
    }

    const formData = new FormData();
    formData.append("image", selectedFile.value);

    try {
        const response = await axios.post("http://localhost:8000/api/user/profile/image", formData, {
            headers: {
                'Authorization': 'Bearer 11|hiaf6gw5GvGF0qjorCKHOMG22Z5LQfYHM9TGQaq13fc7421b'
            }
        });

        console.log(response.status);
        console.log("Success:", response.data);
    } catch (error) {
        console.error("Error:", error);
    }
};

const deleteImage = async () => {
    if (confirm("Are you sure you want to delete your profile image?")) {
        try {
        const response = await axios.delete("http://localhost:8000/api/user/profile/image/45643", {
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
};

const changeImage = async () => {
    if (!selectedFile.value) {
        alert("Please select a file first!");
        return;
    }

    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("image", selectedFile.value);
    

    try {
        const response = await axios.put("http://localhost:8000/api/user/profile/image/45644", formData, {
            headers: {
                // 'Content-Type': 'Multipart/form-data',
                'Authorization': 'Bearer 11|hiaf6gw5GvGF0qjorCKHOMG22Z5LQfYHM9TGQaq13fc7421b'
            }
        });

        console.log(response.status);
        console.log("Success:", response.data);
    } catch (error) {
        console.error("Error:", error);
    }
};
</script> -->
