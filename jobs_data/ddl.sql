drop database if exists blueeconomics;
create database blueeconomics;
use blueeconomics;
drop table if exists blueeconomics.jobs;
create table blueeconomics.jobs(
	JobCode varchar(10),
	IndustryId varchar(7),
	SocCode varchar(7) primary key,
	JobDescription text,
	JobTitle varchar(100),
	Prospects varchar(32),
	AnnualAvgOpenings int,
	EntryEduLevel varchar(64),
	ReqWorkExp varchar(15),
	ReqJobTraining varchar(64),
	AverageEduLevel varchar(64),
	CurrentEmployment int,
	AvgAnnWage int,
	MedianAnnWage int,
	AvgEntryWage int,
	AvgExpWage int,
	BlueEconGrade varchar(10),
	BlueEconScore float,
	EducationScore float,
	GrowthScore float,
	AvailabilityScore float,
	IncomeScore float
)
engine = 'MyISAM'
charset = 'utf8';
create fulltext index job_desc_ix on blueeconomics.jobs(JobDescription);
create fulltext index job_title_ix on blueeconomics.jobs(JobTitle);

drop table if exists blueeconomics.industry;
create table blueeconomics.industry (
	SocCode varchar(7),
	IndustryName varchar(255)
)
engine = 'MyISAM'
charset = 'utf8';
create fulltext index ind_name_ix on blueeconomics.industry(IndustryName);

drop view if exists job_summary;
create view job_summary
as
select
  j.SocCode,
  j.IndustryId,
  i.IndustryName,
  j.EntryEduLevel as Education,
  j.BlueEconScore,
  j.JobTitle,
  j.MedianAnnWage as AnnualIncome,
  j.MedianAnnWage / 2000 as HourlyIncome
from blueeconomics.jobs j, blueeconomics.industry i
where j.INDUSTRYID = i.SocCode;