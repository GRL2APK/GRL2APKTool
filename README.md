# GRL2APKTool
GRL2APK tool is able to generate Android apk file directly from goal model specified using XtGRL notation. This tool involves several technologies such as Acceleo MTL (Model to text transformation Language), Amazon AWS s3 and android libraries. "HealthCare.ndsl" (GRL2APKTool/Input/HealthCare.ndsl) file contains the input goal model written in XtGRL. In the "Tool" folder, different folders are created for containing modules which are responsible to accomplish different tasks. Each folder contains a "readme.txt". "readme.txt" file contains the instructions to deploy the project.
"GRL2APKTool/Output" folder contains the resulting codes that are fetched and integrated from the AWS S3 repository. A workflow file ("GRL2APKTool/Output/Workflow.txt") contains the workflow of the app and based on which the codes are integrated. 
<br>Step 1: First follow the instructions in the "AcceleoCodes" folder to process the goal model.
<br>Step 2: Follow the instructions in "Web Service" folder.
<br>Step 3: Switch to "AcceleoCodes" folder again and follow the instructions in "AcceleoCodes" folder.
<br>Step 4: Follow the instructions in "APK Generator" folder.
<br>Finally, one of the IDE for android platform (in our case Android Studio is used)can be used to import the project and build the apk file. 


