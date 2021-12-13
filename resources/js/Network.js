export async function HttpSend({to, formData, method='POST', url="", type=""}){

  let form = null;
  let header = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }
  if(method == 'POST'){
    form = new FormData()
    
    if(typeof formData === 'object'){
      for (const [key, value] of Object.entries(formData)) {
          form.append(key, value)
      }
    }
  }
  else if(method == 'PUT'){
    form = JSON.stringify(formData)
    header = {...header, 'Content-Type':'application/json; charset=UTF-8'}
  }
  
  return new Promise((resolve, reject)=>{
    fetch(url+''+to, 
      {
        method:method, 
        body:form, 
        headers:header
      })
    .then(function(response){
        if(type)
          return response.blob()
      return response.json()
    })
    .then((res)=>{
      resolve(res)
    })
  });
}