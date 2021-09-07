import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServicesService } from '../services.service';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
	editForm : any;
    submitted  : boolean = false;

  	constructor(private fb:FormBuilder, private servicesService : ServicesService) { }

  	ngOnInit() {
  		this.editForm = this.fb.group({
  			user_name: ['',[Validators.required]],
  			user_password: ['',[Validators.required]]
		});
  	}

  	onSubmit(){
      this.submitted = true;
      if (this.editForm.invalid) {
          return;
      }

      var sendData = new FormData();
      sendData.append('email', this.editForm.value.user_name);
      sendData.append('password', this.editForm.value.user_password);
      this.servicesService.login(sendData).subscribe(response => {
        console.log(response);
      });

    
  		console.log(sendData);
  	}

}
