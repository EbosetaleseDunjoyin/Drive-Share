import axios from "axios"

const http = () => {

    let options = {
      baseURL: "http://127.0.0.1:8000",
      headers: {},
    };

    const token = localStorage.getItem("app-token");
    if(token){
        options.headers.Authorization = `Bearer ${token}`
    }

    return axios.create(options);
}

export default http