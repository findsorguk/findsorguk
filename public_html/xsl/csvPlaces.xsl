<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:sp="http://www.w3.org/2005/sparql-results#"
        xmlns="http://www.w3.org/1999/xhtml">
<xsl:output method="text" omit-xml-declaration="yes" encoding="utf-8" />
<xsl:strip-space elements="*" />
<xsl:template match="sp:result">
  <xsl:value-of select="sp:binding[@name='subject']"/><xsl:text>|</xsl:text>
  <xsl:value-of select="sp:binding[@name='label']"/><xsl:text>|</xsl:text>
  <xsl:value-of select="normalize-space(sp:binding[@name='scopeNote'])"/><xsl:text>|</xsl:text>
  <xsl:value-of select="sp:binding[@name='gender']"/><xsl:text>|</xsl:text>
  <xsl:value-of select="sp:binding[@name='nationality']"/>
  <xsl:text>&#xa;</xsl:text>
</xsl:template>

 
</xsl:stylesheet>