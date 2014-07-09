####Updating the Jobs Dataset
1. Update *Master.xlsx* with the latest data the BLS and other relevant sources
2. Save each tab to the respective txt file
3. Run `data_loader.php --industry industries.txt --jobs national_jobs_data.txt --prospects nyc_bls_prospects_data.txt --wages nyc_bls_wage_data.txt`

Step 3 will load the data, compute custom BlueEconomics score and insert the data into the database.

In addition to this load, the script also loads the original dataset in `DDL_TABDELIMITED_DATA/blueecondbDUMP.sql` into the databases