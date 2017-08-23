#infile<-"model_input789321.txt"
args <- commandArgs(TRUE)	# this line will take the command line parameter into args.
infile <- args[1]			# this line will send args[1] to a

# infile should only have 1 SMILES in it
# if there are more than one, only the first will be used
newchem.smiles<- scan(infile,what=character())[1]

# load libraries
# .libPaths("C:/Users/wchiu/Documents/R/win-library/3.3") # For testing
library("randomForest")
library("rcdk") # Note this requres JAVA that is that same build (e.g., 64-bit) as R

## Set up -- only do this once
load("ToxValRFModels.Rdata")
tv.residuals <- read.csv("Prediction_Residuals.csv",row.names=1)
row.names(tv.residuals) <- ToxValuesNames
dc <- get.desc.categories()
dnames<-c(get.desc.names(dc[1]),get.desc.names(dc[2]),get.desc.names(dc[3]),get.desc.names(dc[4]),get.desc.names(dc[5]))
dnames<-unique(dnames)

## Get descriptors from smiles - do this for each chemical

mol <- parse.smiles(newchem.smiles)[[1]]
mol.desc<-cbind(data.frame(smiles=newchem.smiles,stringsAsFactors = FALSE),eval.desc(mol,dnames))

## Clean up descriptors
mol.desc.clean <- mol.desc[,col.keep]
mol.desc.clean.imputed <- mol.desc.clean

# Impute NA with median of training set (if necessary)
for (i in 1:ncol(mol.desc.clean.imputed)) {
  mol.desc.clean.imputed[is.na(mol.desc.clean.imputed[,i]),i]<-col.med[i]
}

## Make predictions
x.new<-mol.desc.clean.imputed[,-1]
y.pred<-data.frame(tv=sub(".train","",ToxValuesNames),row.names=ToxValuesNames)
y.pred$prediction<-0
y.pred$lower95<-0
y.pred$upper95<-0
y.pred$appl.domain<-0

for (tval in ToxValuesNames) {
  y.pred[tval,"prediction"] <- predict(tv.model[[tval]],x.new)
  y.pred[tval,"lower95"] <- y.pred[tval,"prediction"] + tv.residuals[tval,"CDK.ci.lb"]
  y.pred[tval,"upper95"] <- y.pred[tval,"prediction"] + tv.residuals[tval,"CDK.ci.ub"]
}

## Check applicability domain Z-score

for (tval in ToxValuesNames) {
  ## Combine new chemical and tox value descriptors, and scale
  tv.desc <- tv.train[[tval]][,-(1:2)]
  all.desc <- rbind(x.new,tv.desc)
  all.desc.scale <- as.data.frame(scale(all.desc,center=TRUE,scale=TRUE))
  ## Write ".x" file for new chemical
  newchem_xfile<-paste(infile,"_newchem.x",sep="")
  x.new.scale <- all.desc.scale[1,]
  write.table(as.data.frame(t(dim(x.new.scale))),file=newchem_xfile,
              row.names=FALSE,col.names=FALSE,quote=FALSE,sep="\t")
  write.table(as.data.frame(t(names(x.new.scale))),file=newchem_xfile,
              row.names=FALSE,col.names=FALSE,quote=FALSE,sep="\t",append=TRUE)
  write.table(cbind(newchem.smiles,x.new.scale),file=newchem_xfile,
              row.names=TRUE,col.names=FALSE,quote=FALSE,sep="\t",append=TRUE)
  ## Write ".x" file for tox values
  tv.desc.scale <- all.desc.scale[-1,]
  tv_xfile<-paste(infile,"_",tval,".x",sep="")
  write.table(as.data.frame(t(dim(tv.desc.scale))),file=tv_xfile,
              row.names=FALSE,col.names=FALSE,quote=FALSE,sep="\t")
  write.table(as.data.frame(t(names(tv.desc.scale))),file=tv_xfile,
              row.names=FALSE,col.names=FALSE,quote=FALSE,sep="\t",append=TRUE)
  write.table(cbind(tv.train[[tval]][,1],tv.desc.scale),file=tv_xfile,
              row.names=TRUE,col.names=FALSE,quote=FALSE,sep="\t",append=TRUE)
  ## Calculate z-score
  gad_outfile <- paste(infile,"_newchem_by_",tval,".gad",sep="");
  system(paste("get_ad.exe ",tv_xfile," -4PRED=",newchem_xfile," -Z=3 -OUT=",gad_outfile,sep="")); 
  report.dat<-read.table(gad_outfile,header=TRUE,skip=3);
  y.pred[tval,"appl.domain"]<-report.dat$Z.score
}

##
outfile<-paste(infile,"_output.csv",sep="")
write.csv(y.pred,file=outfile,row.names=FALSE)
