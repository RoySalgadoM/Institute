const url = "http://localhost/institute/server/institute/public/index.php/schools";
const getSchools=() => {
    $.ajax({
        method: "GET",
        url: url
    }).done(function (res) {
        content = "";
        res = res.listSchools
        for (let i = 0; i < res.length; i++) {
            content += `
                        <tr>
                            <td>${res[i].id}</td>
                            <td>${res[i].name}</td>
                            <td>${res[i].street}</td>
                            <td>${res[i].created.date}</td>
                            <td>${res[i].updated.date}</td>
                            <td>${res[i].status}</td>
                            <td>
                                <button type="button" onclick="getSchoolById(${res[i].id})" data-bs-toggle="modal" data-bs-target="#modifySchool" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                            </td>
                            <td>
                                <button onclick="deleteSchool(${res[i].id})" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
        }
        $("#table > tbody").html(content);

    });
};

const getSchoolById = async (id) => {
    await $.ajax({
        method: "GET",
        url: url + '/' + id
    }).done(res =>{
        document.getElementById("streetM").value = res.school[0].street;
        document.getElementById("statusM").value = res.school[0].status ==1?"Activo":"Inactivo";
        document.getElementById("nameM").value = res.school[0].name;
        document.getElementById("idM").value =res.school[0].id;
    });
};

const createSchool= async()=>{
    let school = new Object();
    school.street = document.getElementById("street").value;
    school.status = document.getElementById("status").value;
    school.name = document.getElementById("name").value;
    await $.ajax({
        method: "POST",
        url: url + '/create',
        data: school
    }).done(res =>{
        Swal.fire({
            title: 'The school has been registered',
            confirmButtonText: 'Reload school table',
            icon: 'success',
        }).then((result) => {
            if (result.isConfirmed) {
                getSchools();
                document.getElementById("street").value = "";
                document.getElementById("status").value = "";
                document.getElementById("name").value = "";
                document.getElementById("closeRegister").click();
            }
        })
    });
}

const modifySchool= async()=>{
    let school = new Object();
    school.street = document.getElementById("streetM").value;
    school.status = document.getElementById("statusM").value;
    school.name = document.getElementById("nameM").value;
    school.id = document.getElementById("idM").value;
    await $.ajax({
        method: "POST",
        url: url + '/update',
        data: school
    }).done(res =>{
        Swal.fire({
            title: 'The school has been modify',
            confirmButtonText: 'Reload school table',
            icon: 'success',
        }).then((result) => {
            if (result.isConfirmed) {
                getSchools();
                document.getElementById("closeModify").click();
            }
        })
    });
}
const deleteSchool= async(id)=>{
    let school = new Object();
    school.id = id;
    await $.ajax({
        method: "POST",
        url: url + '/delete/'+id
    }).done(res =>{
        Swal.fire({
            title: 'The school has been delete',
            confirmButtonText: 'Reload school table',
            icon: 'success',
        }).then((result) => {
            if (result.isConfirmed) {
                getSchools();
            }
        })
    });
}