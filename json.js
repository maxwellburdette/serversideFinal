/**
 * File: json.js (Reads json file and populates data to page)
    Server Side Development / Project: Term Project
    Maxwell Burdette / burdettm@csp.edu
    04/26/2021
 */

function getJson() {
	var thisRequest = new XMLHttpRequest()
	thisRequest.open("GET", "jobs.json", true)
	thisRequest.setRequestHeader("Content-type", "application/json", true)
	thisRequest.onreadystatechange = function () {
		if (thisRequest.readyState == 4 && thisRequest.status == 200) {
			var jobs = JSON.parse(thisRequest.responseText)
			let count = 0
			jobs.forEach(job => {
				createRow(job, count)
				displaySampleData(job)
				count++
			})
		}
	}
	thisRequest.send(null)
}

function createRow(job, count) {
	let companyData = job.companyName
	let jobTitleData = job.jobTitle
	let jobDescriptionData = job.jobDescription

	let row = document.createElement("tr")
	row.className = "table table-light"
	let tbody = document.getElementById("data")

	//Create columns
	let company = document.createElement("td")
	company.style.borderRight = "2px solid #dee2e6"
	company.innerText = companyData

	let jobTitle = document.createElement("td")
	jobTitle.style.borderRight = "2px solid #dee2e6"
	let jobInput = document.createElement("input")
	jobInput.className = "form-control"
	jobInput.placeholder = "Job Title"
	jobInput.value = jobTitleData
	jobInput.id = "jobTitle" + count
	jobInput.name = "jobTitle" + count
	jobTitle.append(jobInput)

	let jobDescription = document.createElement("td")
	let jobDescriptionInput = document.createElement("textarea")
	jobDescriptionInput.className = "form-control"
	jobDescriptionInput.style.resize = "none"
	jobDescriptionInput.placeholder = "Job Description"
	jobDescriptionInput.value = jobDescriptionData
	jobDescriptionInput.id = "jobDescription" + count
	jobDescriptionInput.name = "jobDescription" + count
	jobDescription.appendChild(jobDescriptionInput)

	//Add columns to row
	row.appendChild(company)
	row.appendChild(jobTitle)
	row.appendChild(jobDescription)

	tbody.appendChild(row)
}

function displaySampleData(job) {
	let companyData = job.companyName
	let jobTitleData = job.jobTitle
	let jobDescriptionData = job.jobDescription

	let row = document.createElement("tr")
	row.className = "table table-light"
	let tbody = document.getElementById("sample")

	//Create columns
	//Create columns
	let company = document.createElement("td")
	company.style.borderRight = "2px solid #dee2e6"
	company.innerText = companyData

	let jobTitle = document.createElement("td")
	jobTitle.style.borderRight = "2px solid #dee2e6"
	jobTitle.innerText = jobTitleData

	let jobDescription = document.createElement("td")
	jobDescription.innerText = jobDescriptionData

	//Add columns to row
	row.appendChild(company)
	row.appendChild(jobTitle)
	row.appendChild(jobDescription)

	tbody.appendChild(row)
}

getJson()
